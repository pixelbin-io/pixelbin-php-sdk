<?php

declare(strict_types=1);

namespace Pixelbin\Tests\Platform {
    use Mockery as m;
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
    use PHPUnit\Framework\TestCase;
    use Pixelbin\Common\Exceptions\PDKServerResponseError;
    use Pixelbin\Platform\Enums\AccessEnum;
    use Pixelbin\Platform\PixelbinConfig;
    use Pixelbin\Platform\PixelbinClient;
    use Pixelbin\Platform\APIClient;
    use Pixelbin\Common\GuzzleHttpHelper;
    use GuzzleHttp\Psr7\Stream;
    use Pixelbin\Platform\Uploader;
    use Pixelbin\Platform\UploaderUtils\UploadChunkTask;
    use Pixelbin\Tests\Platform\UploaderUtils\UploadChunkTestTask;

    class UploaderTest extends TestCase
    {
        use MockeryPHPUnitIntegration;
        private PixelbinClient $pixelbin;
        private m\Mock|GuzzleHttpHelper $guzzleHttpHelperMock;
        private m\Mock|Uploader $uploaderMock;

        protected function setUp(): void
        {
            parent::setUp();

            // Initialize configuration
            $config = new PixelbinConfig([
                'domain' => 'https://api.pixelbin.io',
                'apiSecret' => 'API_TOKEN'
            ]);

            $this->pixelbin = new PixelbinClient($config);

            // Mock GuzzleHttpHelper
            $this->guzzleHttpHelperMock = m::mock(GuzzleHttpHelper::class);
            $this->uploaderMock = m::mock(Uploader::class . "[uploadChunk]", [$this->pixelbin->assets]);

            // Set the mocked helper to APIClient
            APIClient::$helper = $this->guzzleHttpHelperMock;

            // Mock Uploader and set to the PixelbinClient uploader property
            $this->pixelbin->uploader = $this->uploaderMock;
        }

        protected function tearDown(): void
        {
            parent::tearDown();
        }

        public function testUploadFromBuffer(): void
        {
            $this->guzzleHttpHelperMock->expects()
                ->request(
                    "post",
                    "https://api.pixelbin.io/service/platform/assets/v2.0/upload/signed-url",
                    [],
                    [
                        'name' => 'test.txt',
                        'path' => '/uploads',
                        'format' => 'txt',
                        'access' => AccessEnum::PUBLIC_READ,
                        'tags' => [],
                        'metadata' => (object) [],
                        'overwrite' => true,
                        'filenameOverride' => true,
                        'expiry' => 1500
                    ],
                    m::any()
                )
                ->andReturn(MOCK_RESPONSE["createSignedUrlV2Case1"]["response"]);

            $this->guzzleHttpHelperMock->expects()
                ->request(
                    "POST",
                    m::any(),
                    [],
                    m::any(),
                    [
                        'Content-Type' => 'application/json; charset=utf-8'
                    ]
                )
                ->andReturn(['status' => 'completed']);

            // Mock successful chunk upload
            $this->uploaderMock->shouldReceive("uploadChunk")
                ->withAnyArgs()
                ->andReturn(new UploadChunkTestTask());

            $buffer = 'test file content';
            $result = $this->pixelbin->uploader->upload(
                $buffer,
                'test.txt',
                '/uploads',
                'txt',
                AccessEnum::PUBLIC_READ,
                [],
                (object) [],
                true,
                true,
                1500,
                [
                    'chunkSize' => 5 * 1024 * 1024,
                    'concurrency' => 1
                ]
            );

            $this->assertArrayHasKey('status', $result);
            $this->assertEquals('completed', $result['status']);
        }

        public function testUploadFromStream(): void
        {
            // Create a memory stream
            $stream = fopen('php://memory', 'r+');
            fwrite($stream, 'test stream content');
            rewind($stream);
            $fileStream = new Stream($stream);

            // Mock successful presigned URL response
            $presignedUrlResponse = MOCK_RESPONSE["createSignedUrlV2Case1"]["response"];

            // Mock successful presigned URL response
            $this->guzzleHttpHelperMock->expects()
                ->request(
                    "post",
                    "https://api.pixelbin.io/service/platform/assets/v2.0/upload/signed-url",
                    [],
                    [
                        'name' => 'test.txt',
                        'path' => '/uploads',
                        'format' => 'txt',
                        'access' => AccessEnum::PUBLIC_READ,
                        'tags' => [],
                        'metadata' => (object) [],
                        'overwrite' => true,
                        'filenameOverride' => true,
                        'expiry' => 1500
                    ],
                    m::any()
                )
                ->andReturn($presignedUrlResponse);

            $this->guzzleHttpHelperMock->expects()
                ->request(
                    "POST",
                    m::any(),
                    [],
                    m::any(),
                    [
                        'Content-Type' => 'application/json; charset=utf-8'
                    ]
                )
                ->andReturn(['status' => 'completed']);

            // Mock successful chunk upload
            $this->uploaderMock->shouldReceive("uploadChunk")
                ->withAnyArgs()
                ->andReturn(new UploadChunkTestTask());

            $result = $this->pixelbin->uploader->upload(
                $fileStream,
                'test.txt',
                '/uploads',
                'txt',
                AccessEnum::PUBLIC_READ,
                [],
                (object) [],
                true,
                true,
                1500,
                [
                    'chunkSize' => 5 * 1024 * 1024,
                    'concurrency' => 1
                ]
            );

            $this->assertArrayHasKey('status', $result);
            $this->assertEquals('completed', $result['status']);
        }


        public function testRetryOnFailure(): void
        {
            $presignedUrlResponseContent = MOCK_RESPONSE["createSignedUrlV2Case1"]["response"]["content"];
            $buffer = 'test file content';

            // Mock failed chunk upload followed by success
            $this->guzzleHttpHelperMock->shouldReceive("request")
                ->withSomeOfArgs(
                    "PUT",
                    [],
                    ['Content-Type' => 'multipart/form-data']
                )
                ->twice()
                ->andReturnValues([
                    ['status_code' => 500, 'error_message' => 'Network Error'],
                    ['status_code' => 204]
                ]);

            UploadChunkTask::$helper = $this->guzzleHttpHelperMock;

            $uploaderChunkTask = new UploadChunkTask(
                $presignedUrlResponseContent["presignedUrl"]["url"],
                $presignedUrlResponseContent["presignedUrl"]["fields"],
                $buffer,
                1,
                [
                    'chunkSize' => 5 * 1024 * 1024,
                    'concurrency' => 1,
                    'maxRetries' => 2,
                    'exponentialFactor' => 2
                ]
            );

            $uploaderChunkTask->run();
        }

        public function testNoRetryOn4xxErrorsChunkUpload(): void
        {
            $this->expectException(PDKServerResponseError::class);
            $this->expectExceptionMessage('Bad Request');

            $presignedUrlResponseContent = MOCK_RESPONSE["createSignedUrlV2Case1"]["response"]["content"];
            $buffer = 'test file content';

            $this->guzzleHttpHelperMock->shouldReceive("request")
                ->withSomeOfArgs(
                    "PUT",
                    [],
                    ['Content-Type' => 'multipart/form-data']
                )
                ->once()
                ->andThrow(new PDKServerResponseError('Bad Request', 400));

            UploadChunkTask::$helper = $this->guzzleHttpHelperMock;

            $uploaderChunkTask = new UploadChunkTask(
                $presignedUrlResponseContent["presignedUrl"]["url"],
                $presignedUrlResponseContent["presignedUrl"]["fields"],
                $buffer,
                1,
                [
                    'chunkSize' => 5 * 1024 * 1024,
                    'concurrency' => 1,
                    'maxRetries' => 2,
                    'exponentialFactor' => 2
                ]
            );

            $result = $uploaderChunkTask->run();

            $uploaderChunkTask->run();
        }

        public function testNoRetryOn4xxErrorsCompleteUpload(): void
        {
            $this->expectException(PDKServerResponseError::class);
            $this->expectExceptionMessage('Bad Request');
            $this->expectExceptionCode(400);

            // Mock successful presigned URL response
            $presignedUrlResponse = MOCK_RESPONSE["createSignedUrlV2Case1"]["response"];

            // Mock successful presigned URL response
            $this->guzzleHttpHelperMock->expects()
                ->request(
                    "post",
                    "https://api.pixelbin.io/service/platform/assets/v2.0/upload/signed-url",
                    [],
                    [
                        'name' => 'test.txt',
                        'path' => '/uploads',
                        'format' => 'txt',
                        'access' => AccessEnum::PUBLIC_READ,
                        'tags' => [],
                        'metadata' => (object) [],
                        'overwrite' => true,
                        'filenameOverride' => true,
                        'expiry' => 1500
                    ],
                    m::any()
                )
                ->andReturn($presignedUrlResponse);

            // Mock successful chunk upload
            $this->uploaderMock->shouldReceive("uploadChunk")
                ->withAnyArgs()
                ->andReturn(new UploadChunkTestTask());

            // Mock 400 error on upload completion
            $this->guzzleHttpHelperMock->expects()
                ->request(
                    "POST",
                    m::any(),
                    [],
                    m::any(),
                    [
                        'Content-Type' => 'application/json; charset=utf-8'
                    ]
                )
                ->andThrow(new PDKServerResponseError('Bad Request', 400));

            $buffer = 'test file content';
            $this->pixelbin->uploader->upload(
                $buffer,
                'test.txt',
                '/uploads',
                'txt',
                AccessEnum::PUBLIC_READ,
                [],
                (object) [],
                true,
                true,
                1500,
                [
                    'chunkSize' => 5 * 1024 * 1024,
                    'concurrency' => 1,
                    'maxRetries' => 2
                ]
            );
        }
    }
}
