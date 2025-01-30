<?php

declare(strict_types=1);

namespace Pixelbin\Tests\Platform {
    use Pixelbin\Common\{
        Exceptions,
    };

    use Pixelbin\Platform\{
        PixelbinConfig,
        PixelbinClient,
        Assets,
        Organization
    };

    use PHPUnit\Framework\TestCase;
    use Exception;

    const CONFIG = [
        "host" => "api.pixelbin.io",
        "domain" => "https://api.pixelbin.io",
        "apiSecret" => "API_TOKEN"
    ];

    final class PixelbinConfigTest extends TestCase
    {
        // switch to false to hit the PixelBin APIs while testing
        public bool $enableMocking = true;
        public array $config;
        public PixelbinConfig $pixelbinConfig;
        public PixelbinClient $pixelbinClient;

        public function setUp(): void
        {
            $this->config = CONFIG;
            $this->pixelbinConfig = new PixelbinConfig($this->config);
            $this->pixelbinClient = new PixelbinClient($this->pixelbinConfig);
        }

        public function test_pixelbin_config_and_client(): void
        {
            $this->assertEquals($this->config["domain"], $this->pixelbinConfig->domain);
            $this->assertEquals($this->config["apiSecret"], $this->pixelbinConfig->apiSecret);

            $this->assertEquals($this->pixelbinClient->config, $this->pixelbinConfig);
            $this->assertInstanceOf(Assets::class, $this->pixelbinClient->assets);
            $this->assertInstanceOf(Organization::class, $this->pixelbinClient->organization);
        }

        public function test_pixelbin_config_token_1(): void
        {
            try {
                $config = new PixelbinConfig([
                    "domain" => "https://api.pixelbin.io",
                ]);
                new PixelbinClient($config);
            } catch (Exception $e) {
                $this->assertInstanceOf(Exceptions\PDKInvalidCredentialError::class, $e);
                $this->assertTrue(str_contains("No API Secret Token Present", $e->getMessage()));
                return;
            }
            $this->fail("Expected Exceptions\PDKInvalidCredentialError was not thrown.");
        }

        public function test_pixelbin_config_token_2(): void
        {
            try {
                $config = new PixelbinConfig([
                    "domain" => "https://api.pixelbin.io",
                    "apiSecret" => "abc",
                ]);
                new PixelbinClient($config);
            } catch (Exception $e) {
                $this->assertInstanceOf(Exceptions\PDKInvalidCredentialError::class, $e);
                $this->assertTrue(str_contains("Invalid API Secret Token", $e->getMessage()));
                return;
            }
            $this->fail("Expected Exceptions\PDKInvalidCredentialError was not thrown.");
        }
    }
}
