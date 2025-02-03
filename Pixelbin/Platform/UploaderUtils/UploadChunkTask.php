<?php

namespace Pixelbin\Platform\UploaderUtils {
    use Pixelbin\Common\Exceptions;
    use Pixelbin\Common\GuzzleHttpHelper;
    use Amp\Parallel\Worker;
    use Amp\Sync\Channel;
    use Amp\Cancellation;
    use Amp\NullCancellation;

    class UploadChunkTask implements Worker\Task
    {
        private string $url;
        private array $fields;
        private string $chunk;
        private int $partNumber;
        private array $options;
        public static ?GuzzleHttpHelper $helper = null;

        public function __construct(string $url, array $fields, string $chunk, int $partNumber, array $options)
        {
            $this->url = $url;
            $this->fields = $fields;
            $this->chunk = $chunk;
            $this->partNumber = $partNumber;
            $this->options = $options;
        }

        public function run(?Channel $channel = null, ?Cancellation $cancellation = null): mixed
        {
            $retries = 0;
            $cancellation ??= new NullCancellation();

            while (true) {
                try {
                    // Check if the task has been cancelled
                    if ($cancellation->isRequested()) {
                        throw new \Exception("Upload cancelled");
                    }

                    $formData = $this->prepareChunkFormData($this->fields, $this->chunk);
                    $uploadUrl = $this->appendPartNumber($this->url, $this->partNumber);

                    if (static::$helper === null)
                        static::$helper = new GuzzleHttpHelper();

                    $response = static::$helper->request(
                        "PUT",
                        $uploadUrl,
                        [],
                        $formData,
                        [
                            'Content-Type' => 'multipart/form-data'
                        ]
                    );

                    if ($response["status_code"] != 204) {
                        throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
                    }

                    // Send progress update through channel
                    if (!empty($channel)) {
                        $channel->send([
                            'status' => 'progress',
                            'partNumber' => $this->partNumber,
                            'completed' => true
                        ]);
                    }

                    return [
                        'partNumber' => $this->partNumber,
                        'status' => 'success'
                    ];
                } catch (\Exception $e) {
                    if (
                        $e instanceof Exceptions\PDKServerResponseError &&
                        $e->getCode() >= 400 && $e->getCode() < 500
                    ) {
                        throw $e;
                    }

                    if ($retries < $this->options['maxRetries']) {
                        $retries++;
                        $delay = pow($this->options['exponentialFactor'], $retries) * 1000;

                        // Send retry status through channel
                        if (!empty($channel)) {
                            $channel->send([
                                'status' => 'retry',
                                'partNumber' => $this->partNumber,
                                'attempt' => $retries
                            ]);
                        }

                        usleep($delay * 1000);
                        continue;
                    }
                    throw $e;
                }
            }
        }

        private function prepareChunkFormData(array $fields, string $chunk): array
        {
            $formData = [];

            // Add all fields from the presigned URL
            foreach ($fields as $key => $value) {
                $formData[$key] = $value;
            }

            // Add the file chunk
            $formData['file'] = $chunk;

            return $formData;
        }

        private function appendPartNumber(string $url, int $partNumber): string
        {
            $urlObj = parse_url($url);
            $query = isset($urlObj['query']) ? $urlObj['query'] . '&' : '';
            $query .= "partNumber=" . $partNumber;

            return $urlObj['scheme'] . '://' . $urlObj['host'] . $urlObj['path'] .
                (empty($query) ? '' : "?$query");
        }
    }
}
