<?php

declare(strict_types=1);

namespace Pixelbin\Tests\Platform {
    use PHPUnit\Framework\MockObject\MockObject;
    use Pixelbin\Common\{
        GuzzleHttpHelper,
        Utils
    };

    use Pixelbin\Platform\{
        APIClient,
        PixelbinConfig,
        PixelbinClient,
    };

    use PHPUnit\Framework\TestCase;

    final class UserAgentTest extends TestCase
    {
        // switch to false to hit the PixelBin APIs while testing
        public array $config;
        public PixelbinConfig $pixelbinConfig;
        public PixelbinClient $pixelbinClient;
        public MockObject|GuzzleHttpHelper $guzzleHttpHelperMock;

        public function setMockObjectExpectations(array $mockResponse, array $calledWithArguments): void
        {
            $this->guzzleHttpHelperMock
                ->expects($this->once())
                ->method("request")
                ->with(...$calledWithArguments)
                ->willReturn($mockResponse);
            APIClient::$helper = $this->guzzleHttpHelperMock;
        }

        public function checkUserAgentHeader(array $data, array $config): bool
        {
            $sdk = Utils::getSdkDetails();

            $userAgent = $sdk["name"] . "/" . $sdk["version"] . " (php)";
            if (array_key_exists("integrationPlatform", $config)) {
                $userAgent = $config["integrationPlatform"] . " " . $userAgent;
            }

            $this->assertStringEqualsStringIgnoringLineEndings($userAgent, $data["User-Agent"]);

            return true;
        }

        public function setUp(): void
        {
            $this->guzzleHttpHelperMock = $this->createMock(GuzzleHttpHelper::class);
        }

        public function test_UserAgentHeaderWithIntegrationPlatform()
        {
            // Set up PixelbinClient
            $this->config = [
                "domain" => "https://api.pixelbin.io",
                "apiSecret" => "test-api-secret",
                "integrationPlatform" => "Erasebg Plugin/1.2.3 (Figma/3.2.1)",
            ];

            $this->pixelbinConfig = new PixelbinConfig($this->config);
            $this->pixelbinClient = new PixelbinClient($this->pixelbinConfig);

            $mock_response = MOCK_RESPONSE["listFiles1"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/listFiles",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data, $this->config);
                    }),
                ]
            );

            $this->pixelbinClient->assets->listFiles();
        }

        public function test_UserAgentHeaderWithoutIntegrationPlatform()
        {
            // Set up PixelbinClient
            $this->config = [
                "domain" => "https://api.pixelbin.io",
                "apiSecret" => "test-api-secret",
            ];

            $this->pixelbinConfig = new PixelbinConfig($this->config);
            $this->pixelbinClient = new PixelbinClient($this->pixelbinConfig);

            $mock_response = MOCK_RESPONSE["listFiles1"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/listFiles",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data, $this->config);
                    }),
                ]
            );

            $this->pixelbinClient->assets->listFiles();
        }
    }
}
