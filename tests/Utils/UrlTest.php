<?php

declare(strict_types=1);

namespace Pixelbin\Tests\Utils {
    require_once(__DIR__ . "/test_utils.php");

    use Pixelbin\Utils\{
        Url,
    };
    use Pixelbin\Common\{
        Exceptions,
    };

    use PHPUnit\Framework\TestCase;
    use Exception;

    final class UrlTest extends TestCase
    {
        // switch to false to hit the PixelBin APIs while testing
        public array $urls_to_obj;
        public array $objs_to_url;

        public function setUp(): void
        {

            // Create Data
            $this->urls_to_obj = URLS_TO_OBJ;
            $this->objs_to_url = OBJS_TO_URL;
        }

        public function test_UrlToObj()
        {
            foreach ($this->urls_to_obj as $case) {
                $url = $case["url"];
                $expectedObj = $case["obj"];
                $obj = Url::url_to_obj($url);
                $this->assertEquals($expectedObj, $obj);
            }
        }

        public function test_ObjToUrl()
        {
            foreach ($this->objs_to_url as $case) {
                $obj = $case["obj"];
                $expectedUrl = $case["url"];
                try {
                    $url = Url::obj_to_url($obj);
                    $this->assertEquals($expectedUrl, $url);
                } catch (Exception $err) {
                    $this->assertEquals($err->getMessage(), $case["error"]);
                }
            }
        }

        public function test_FailureForOptionDprQueryParam()
        {
            $obj = [
                "baseUrl" => "https://cdn.pixelbin.io",
                "filePath" => "__playground/playground-default.jpeg",
                "version" => "v2",
                "zone" => "z-slug",
                "cloudName" => "red-scene-95b6ea",
                "options" => ["dpr" => 5.5, "f_auto" => true],
                "transformations" => [[]],
            ];

            $this->expectException(Exceptions\PixelbinIllegalQueryParameterError::class);
            Url::obj_to_url($obj);
        }

        public function test_FailureForOptionFautoQueryParam()
        {
            $obj = [
                "baseUrl" => "https://cdn.pixelbin.io",
                "filePath" => "__playground/playground-default.jpeg",
                "version" => "v2",
                "zone" => "z-slug",
                "cloudName" => "red-scene-95b6ea",
                "options" => ["dpr" => 2.5, "f_auto" => "abc"],
                "transformations" => [[]],
            ];

            $this->expectException(Exceptions\PixelbinIllegalQueryParameterError::class);
            Url::obj_to_url($obj);
        }
    }
}
