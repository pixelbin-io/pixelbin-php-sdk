<?php

namespace Pixelbin\Platform {
    use Pixelbin\Common\Exceptions;
    use Pixelbin\Platform\Assets;
    use Pixelbin\Platform\Enums\AccessEnum;
    use Pixelbin\Platform\UploaderUtils\UploadChunkTask;
    use Psr\Http\Message\StreamInterface;
    use Amp\Future;
    use Amp\Parallel\Worker;
    use Pixelbin\Platform\APIClient;

    class Uploader
    {
        private Assets $assets;

        public function __construct(Assets $assets)
        {
            $this->assets = $assets;
        }

        public function upload(
            StreamInterface|string $file,
            string $name,
            string $path,
            string $format,
            AccessEnum $access,
            array $tags,
            object $metadata,
            bool $overwrite,
            bool $filenameOverride,
            int $expiry,
            array $uploadOptions = [],
        ): array {
            // Validate and set default upload options
            $this->validateUploadOptions($uploadOptions);

            // Get presigned URL
            $presignedUrl = $this->assets->createSignedUrlV2(
                $name,
                $path,
                $format,
                $access,
                $tags,
                $metadata,
                $overwrite,
                $filenameOverride,
                $expiry
            );

            return $this->multipartUploadToPixelbin(
                $presignedUrl['presignedUrl']['url'],
                $presignedUrl['presignedUrl']['fields'],
                $file,
                $uploadOptions,
            );
        }

        private function multipartUploadToPixelbin(string $url, array $fields, StreamInterface|string $file, array $options): array
        {
            $chunks = [];

            if ($file instanceof StreamInterface) {
                while (!$file->eof()) {
                    $chunk = $file->read($options['chunkSize']);
                    $chunks[] = $chunk;
                }
            } else {
                $fileLength = strlen($file);
                for ($i = 0; $i < $fileLength; $i += $options['chunkSize']) {
                    $chunks[] = substr($file, $i, $options['chunkSize']);
                }
            }

            $partNumber = 0;
            // Upload chunks concurrently
            $uploadTasks = [];
            foreach ($chunks as $index => $chunk) {
                $partNumber = $index + 1;
                $uploadTasks[] = Worker\submit($this->uploadChunk($url, $fields, $chunk, $partNumber, $options));
            }

            // Wait for all uploads to complete
            try {
                $responses = Future\await(array_map(
                    fn (Worker\Execution $e) => $e->getFuture(),
                    $uploadTasks,
                ));
            } catch (\Exception $e) {
                throw new Exceptions\PDKServerResponseError(
                    "Failed to upload all chunks: " . $e->getMessage(),
                    500
                );
            }

            return $this->completeMultipartUpload(
                $url,
                $fields,
                $partNumber,
                $options['maxRetries'],
                $options['exponentialFactor']
            );
        }

        public function uploadChunk(string $url, array $fields, string $chunk, int $partNumber, array $options): Worker\Task
        {
            return new UploadChunkTask($url, $fields, $chunk, $partNumber, $options);
        }

        private function completeMultipartUpload($url, $fields, $partNumber, $maxRetries, $exponentialFactor): array
        {
            $retries = 0;
            $urlObj = parse_url($url);
            $query = [];
            parse_str($urlObj['query'], $query);

            while ($retries <= $maxRetries) {
                try {
                    $response = APIClient::$helper->request(
                        "POST",
                        $urlObj['scheme'] . '://' . $urlObj['host'] . $urlObj['path'] . '?' . $urlObj['query'],
                        [],
                        [
                            'parts' => range(1, $partNumber),
                            ...$fields
                        ],
                        [
                            "Content-Type" => 'application/json; charset=utf-8'
                        ]
                    );

                    return $response;
                } catch (\Exception $e) {
                    if (
                        $e instanceof Exceptions\PDKServerResponseError &&
                        $e->getCode() >= 400 && $e->getCode() < 500
                    ) {
                        throw $e;
                    }

                    if ($retries < $maxRetries) {
                        $retries++;
                        $delay = pow($exponentialFactor, $retries) * 1000;
                        usleep($delay * 1000);
                        continue;
                    }
                    throw $e;
                }
            }
        }

        private function validateUploadOptions(array &$uploadOptions): void
        {
            $uploadOptions['chunkSize'] = $uploadOptions['chunkSize'] ?? 10 * 1024 * 1024;
            if (!is_int($uploadOptions['chunkSize']) || $uploadOptions['chunkSize'] <= 0) {
                throw new Exceptions\PDKIllegalArgumentError("Invalid chunkSize: Must be a positive integer");
            }

            $uploadOptions['maxRetries'] = $uploadOptions['maxRetries'] ?? 2;
            if (!is_int($uploadOptions['maxRetries']) || $uploadOptions['maxRetries'] < 0) {
                throw new Exceptions\PDKIllegalArgumentError("Invalid maxRetries: Must be a non-negative integer");
            }

            $uploadOptions['concurrency'] = $uploadOptions['concurrency'] ?? 3;
            if (!is_int($uploadOptions['concurrency']) || $uploadOptions['concurrency'] <= 0) {
                throw new Exceptions\PDKIllegalArgumentError("Invalid concurrency: Must be a positive integer");
            }

            $uploadOptions['exponentialFactor'] = $uploadOptions['exponentialFactor'] ?? 2;
            if (!is_numeric($uploadOptions['exponentialFactor']) || $uploadOptions['exponentialFactor'] < 0) {
                throw new Exceptions\PDKIllegalArgumentError(
                    "Invalid exponentialFactor: Must be a non-negative number"
                );
            }
        }
    }
}
