<?php

declare(strict_types=1);

namespace Pixelbin\Tests\Utils {

    use Pixelbin\Utils\Url\UrlTransformation;

    require_once(__DIR__ . "/test_utils.php");

    use Pixelbin\Utils\{
        Url\Url,
        Url\UrlObj,
        Url\UrlObjOptions,
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

        public function test_1_GetObjFromUrl()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/red-scene-95b6ea/t.resize()/__playground/playground-default.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize"
                    )
                ],
                cloudName: "red-scene-95b6ea",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.resize()",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_2_GetObjFromUrl()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/dill-doe-36b4fc/t.rotate(a:102)/__playground/playground-default.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "rotate",
                        values: [
                            ["key" => "a", "value" => "102"]
                        ]
                    )
                ],
                cloudName: "dill-doe-36b4fc",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.rotate(a:102)",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_3_GetObjFromUrlNoVersion()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/red-scene-95b6ea/t.resize()/__playground/playground-default.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize"
                    )
                ],
                cloudName: "red-scene-95b6ea",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.resize()",
                version: "v1",
                filePath: "__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_4_GetObjFromUrlWithZoneSlug()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/red-scene-95b6ea/zonesl/t.resize()/__playground/playground-default.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize"
                    )
                ],
                cloudName: "red-scene-95b6ea",
                zone: "zonesl",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.resize()",
                version: "v1",
                filePath: "__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_5_GetObjFromUrl_Error()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/sparkling-moon-a7b75b/t.resize(w:200,h:200)/upload/p1/w/random.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize",
                        values: [
                            ["key" => "w", "value" => "200"],
                            ["key" => "h", "value" => "200"]
                        ]
                    )
                ],
                cloudName: "sparkling-moon-a7b75b",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.resize(w:200,h:200)",
                version: "v2",
                filePath: "upload/p1/w/random.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_6_GetObjFromUrl_Error1()
        {
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Invalid pixelbin url. Please make sure the url is correct.");
            Url::url_to_obj("https://cdn.pixelbin.io/v2");
        }

        public function test_7_GetObjFromUrl_Error2()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/doc/original/searchlight/platform-panel/settings/policy/faq/add-faq-group.png");
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "doc",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "original",
                version: "v1",
                filePath: "searchlight/platform-panel/settings/policy/faq/add-faq-group.png",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_8_GetObjFromUrl_Error3()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/dac/ek69d0/original/__playground/playground-default.jpeg");
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "dac",
                zone: "ek69d0",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "original",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_9_GetObjFromUrl2()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/red-scene-95b6ea/t.resize(h:200,w:100)/__playground/playground-default.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize",
                        values: [
                            ["key" => "h", "value" => "200"],
                            ["key" => "w", "value" => "100"]
                        ]
                    )
                ],
                cloudName: "red-scene-95b6ea",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.resize(h:200,w:100)",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_10_GetObjFromUrl3()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/red-scene-95b6ea/t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()/__playground/playground-default.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize",
                        values: [
                            ["key" => "h", "value" => "200"],
                            ["key" => "w", "value" => "100"],
                            ["key" => "fill", "value" => "999"]
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "erase",
                        name: "bg"
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "extend"
                    )
                ],
                cloudName: "red-scene-95b6ea",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_11_GetObjFromUrlWithPreset()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/red-scene-95b6ea/z-slug/t.compress()~t.resize()~t.extend()~p.apply(n:presetNameXyx)/alien_fig_tree_planet_x_wallingford_seattle_washington_usa_517559.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "compress"
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize"
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "extend"
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "apply",
                        values: [
                            ["key" => "n", "value" => "presetNameXyx"]
                        ]
                    )
                ],
                cloudName: "red-scene-95b6ea",
                zone: "z-slug",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.compress()~t.resize()~t.extend()~p.apply(n:presetNameXyx)",
                version: "v2",
                filePath: "alien_fig_tree_planet_x_wallingford_seattle_washington_usa_517559.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_12_GetObjFromUrlWithPreset_Error()
        {
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Invalid pixelbin url. Please make sure the url is correct.");
            Url::url_to_obj("https://cdn.pixelbin.io/v3/red-scene-95b6ea/t.compress()~t.resize()~t.extend()~p.apply(n:presetNameXyx)/alien_fig_tree_planet_x_wallingford_seattle_washington_usa_517559.jpeg");
        }

        public function test_13_HandleIncorrectUrls()
        {
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Invalid pixelbin url. Please make sure the url is correct.");
            Url::url_to_obj("https://cdn.pixelbin.io//v2/dill-doe-36b4fc/original~original/__playground/playground-default.jpeg");
        }

        public function test_14_HandleIncorrectUrls_IncorrectZone()
        {
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Error Processing url. Please check the url is correct");
            Url::url_to_obj("https://cdn.pixelbin.io/v2/red-scene-95b6ea/test/t.compress()~t.resize()~t.extend()~p.apply(n:presetNameXyx)/alien_fig_tree_planet_x_wallingford_seattle_washington_usa_517559.jpeg");
        }

        public function test_15_HandleIncorrectUrls_IncorrectPattern()
        {
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Error Processing url. Please check the url is correct");
            Url::url_to_obj("https://cdn.pixelbin.io/v2/red-scene-95b6ea/t.compress~t.resize()~t.extend()~p.apply(n:presetNameXyx)/alien_fig_tree_planet_x_wallingford_seattle_washington_usa_517559.jpeg");
        }

        public function test_16_HandleIncorrectUrls_IncorrectPattern2()
        {
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Error Processing url. Please check the url is correct");
            Url::url_to_obj("https://cdn.pixelbin.io/v2/red-scene-95b6ea/zonesls/t.resize()/__playground/playground-default.jpeg");
        }

        public function test_17_GetObjFromUrl_WorkerPath_FullPathWithDepthEquals1()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/red-scene-95b6ea/wrkr/image.jpeg");
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "red-scene-95b6ea",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "",
                version: "v2",
                filePath: "",
                worker: true,
                workerPath: "image.jpeg",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_18_GetObjFromUrl_WorkerPath_FullPathWithDepthGreaterThan1()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/falling-surf-7c8bb8/wrkr/misc/general/free/original/images/favicon.ico");
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "falling-surf-7c8bb8",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "",
                version: "v2",
                filePath: "",
                worker: true,
                workerPath: "misc/general/free/original/images/favicon.ico",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_19_GetObjFromUrl_WorkerPathWithZone_FullPathWithDepthEquals1()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/falling-surf-7c8bb8/fyndnp/wrkr/robots.txt");
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "falling-surf-7c8bb8",
                zone: "fyndnp",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "",
                version: "v2",
                filePath: "",
                worker: true,
                workerPath: "robots.txt",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_20_GetObjFromUrl_WorkerPathWithZone_FullPathWithDepthGreaterThan1()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/falling-surf-7c8bb8/fyprod/wrkr/misc/general/free/original/images/favicon.ico");
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "falling-surf-7c8bb8",
                zone: "fyprod",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "",
                version: "v2",
                filePath: "",
                worker: true,
                workerPath: "misc/general/free/original/images/favicon.ico",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_21_UrlFromObj()
        {
            $obj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize",
                        values: [
                            ["key" => "h", "value" => "200"],
                            ["key" => "w", "value" => "100"],
                            ["key" => "fill", "value" => "999"],
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "erase",
                        name: "bg"
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "extend"
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1"
                    )
                ],
                cloudName: "red-scene-95b6ea",
                zone: "z-slug",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                version: "v2",
                filePath: "__playground/playground-default.jpeg"
            );

            $expectedUrl = "https://cdn.pixelbin.io/v2/red-scene-95b6ea/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()~p:preset1/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);

            $obj->version = "v1";

            $expectedUrl = "https://cdn.pixelbin.io/v1/red-scene-95b6ea/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()~p:preset1/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);
        }

        public function test_22_UrlFromObj1()
        {
            $obj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize",
                        values: [
                            ["key" => "h", "value" => "200"],
                            ["key" => "w", "value" => "100"],
                            ["key" => "fill", "value" => "999"],
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "erase",
                        name: "bg",
                        values: [
                            ["key" => "i", "value" => "general"],
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "extend"
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1"
                    )
                ],
                isCustomDomain: false,
                cloudName: "red-scene-95b6ea",
                zone: "z-slug",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                version: "v2",
                filePath: "__playground/playground-default.jpeg"
            );

            $expectedUrl = "https://cdn.pixelbin.io/v2/red-scene-95b6ea/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg(i:general)~t.extend()~p:preset1/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);

            $obj->version = "v1";

            $expectedUrl = "https://cdn.pixelbin.io/v1/red-scene-95b6ea/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg(i:general)~t.extend()~p:preset1/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);
        }

        public function test_23_ThrowErrorIfObjectIsIncorrect()
        {
            $obj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize",
                        values: [
                            ["key" => ""],
                            ["key" => "w", "value" => "100"],
                            ["key" => "fill", "value" => "999"],
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "erase",
                        name: "bg",
                        values: [
                            ["key" => "i", "value" => "general"],
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "extend"
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1"
                    )
                ],
                isCustomDomain: false,
                cloudName: "red-scene-95b6ea",
                zone: "z-slug",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                version: "v2",
                filePath: "__playground/playground-default.jpeg"
            );

            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("key not specified in 'resize'");
            Url::obj_to_url($obj);

            $obj->version = "v1";

            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("key not specified in 'resize'");
            Url::obj_to_url($obj);
        }

        public function test_24_ThrowErrorIfObjectIsIncorrect1()
        {
            $obj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize",
                        values: [
                            ["key" => "h"],
                            ["key" => "w", "value" => "100"],
                            ["key" => "fill", "value" => "999"],
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "erase",
                        name: "bg",
                        values: [
                            ["key" => "i", "value" => "general"],
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "extend"
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1"
                    )
                ],
                isCustomDomain: false,
                cloudName: "red-scene-95b6ea",
                zone: "z-slug",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                version: "v2",
                filePath: "__playground/playground-default.jpeg"
            );

            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("value not specified for key 'h' in 'resize'");
            Url::obj_to_url($obj);

            $obj->version = "v1";

            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("value not specified for key 'h' in 'resize'");
            Url::obj_to_url($obj);
        }

        public function test_25_ThrowErrorIfWorkerIsTrueButWorkerPathIsNotDefined()
        {
            $obj = new UrlObj(
                transformations: [],
                isCustomDomain: false,
                cloudName: "red-scene-95b6ea",
                zone: "z-slug",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                worker: true
            );

            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("key workerPath should be defined");
            Url::obj_to_url($obj);
        }

        public function test_26_UrlFromObjWhenEmpty()
        {
            $obj = new UrlObj(
                transformations: [],
                isCustomDomain: false,
                cloudName: "red-scene-95b6ea",
                zone: "z-slu1",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                version: "v2",
                filePath: "__playground/playground-default.jpeg"
            );

            $expectedUrl = "https://cdn.pixelbin.io/v2/red-scene-95b6ea/z-slu1/original/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);

            $obj->version = "v1";

            $expectedUrl = "https://cdn.pixelbin.io/v1/red-scene-95b6ea/z-slu1/original/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);
        }

        public function test_27_UrlFromObjWhenUndefined()
        {
            $obj = new UrlObj(
                isCustomDomain: false,
                cloudName: "red-scene-95b6ea",
                zone: "z-slug",
                baseUrl: "https://cdn.pixelbin.io",
                version: "v2",
                filePath: "__playground/playground-default.jpeg"
            );

            $expectedUrl = "https://cdn.pixelbin.io/v2/red-scene-95b6ea/z-slug/original/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);

            $obj->version = "v1";

            $expectedUrl = "https://cdn.pixelbin.io/v1/red-scene-95b6ea/z-slug/original/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);
        }

        public function test_28_UrlFromObj_EmptyObject()
        {
            $obj = new UrlObj(
                transformations: [
                    new UrlTransformation()
                ],
                isCustomDomain: false,
                cloudName: "red-scene-95b6ea",
                zone: "z-slug",
                baseUrl: "https://cdn.pixelbin.io",
                version: "v2",
                filePath: "__playground/playground-default.jpeg"
            );

            $expectedUrl = "https://cdn.pixelbin.io/v2/red-scene-95b6ea/z-slug/original/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);

            $obj->version = "v1";

            $expectedUrl = "https://cdn.pixelbin.io/v1/red-scene-95b6ea/z-slug/original/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);
        }

        public function test_29_ThrowErrorIfFilePathIsNotDefined()
        {
            $obj = new UrlObj(
                transformations: [],
                isCustomDomain: false,
                cloudName: "red-scene-95b6ea",
                zone: "z-slug",
                baseUrl: "https://cdn.pixelbin.io",
                version: "v2",
                filePath: ""
            );

            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("key filePath should be defined");
            Url::obj_to_url($obj);
        }

        public function test_30_ObjFromUrl_WithDprEqualsAuto()
        {
            $expectedObj = new UrlObj(
                protocol: "https",
                host: "cdn.pixelbin.io",
                search: "dpr=auto&f_auto=true",
                baseUrl: "https://cdn.pixelbin.io",
                filePath: "MZZKB3e1hT48o0NYJ2Kxh.jpeg",
                pattern: "erase.bg(shadow:true)~t.merge(m:underlay,i:eU44YkFJOHlVMmZrWVRDOUNTRm1D,b:screen,r:true)",
                version: "v2",
                zone: "",
                cloudName: "feel",
                options: new UrlObjOptions(
                    dpr: "auto",
                    f_auto: true
                ),
                worker: false,
                workerPath: "",
                transformations: [
                    new UrlTransformation(
                        plugin: "erase",
                        name: "bg",
                        values: [
                            ["key" => "shadow", "value" => "true"]
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "merge",
                        values: [
                            ["key" => "m", "value" => "underlay"],
                            ["key" => "i", "value" => "eU44YkFJOHlVMmZrWVRDOUNTRm1D"],
                            ["key" => "b", "value" => "screen"],
                            ["key" => "r", "value" => "true"]
                        ]
                    )
                ]
            );
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/feel/erase.bg(shadow:true)~t.merge(m:underlay,i:eU44YkFJOHlVMmZrWVRDOUNTRm1D,b:screen,r:true)/MZZKB3e1hT48o0NYJ2Kxh.jpeg?dpr=auto&f_auto=true");

            $this->assertEquals($expectedObj, $obj);
        }

        public function test_31_ObjFromUrl_WithOptionsIfAvailable()
        {
            $expectedObj = new UrlObj(
                protocol: "https",
                host: "cdn.pixelbin.io",
                search: "dpr=2.5&f_auto=true",
                baseUrl: "https://cdn.pixelbin.io",
                filePath: "MZZKB3e1hT48o0NYJ2Kxh.jpeg",
                pattern: "erase.bg(shadow:true)~t.merge(m:underlay,i:eU44YkFJOHlVMmZrWVRDOUNTRm1D,b:screen,r:true)",
                version: "v2",
                zone: "",
                cloudName: "feel",
                options: new UrlObjOptions(
                    dpr: 2.5,
                    f_auto: true
                ),
                worker: false,
                workerPath: "",
                transformations: [
                    new UrlTransformation(
                        plugin: "erase",
                        name: "bg",
                        values: [
                            ["key" => "shadow", "value" => "true"]
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "merge",
                        values: [
                            ["key" => "m", "value" => "underlay"],
                            ["key" => "i", "value" => "eU44YkFJOHlVMmZrWVRDOUNTRm1D"],
                            ["key" => "b", "value" => "screen"],
                            ["key" => "r", "value" => "true"]
                        ]
                    )
                ]
            );
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/feel/erase.bg(shadow:true)~t.merge(m:underlay,i:eU44YkFJOHlVMmZrWVRDOUNTRm1D,b:screen,r:true)/MZZKB3e1hT48o0NYJ2Kxh.jpeg?dpr=2.5&f_auto=true");

            $this->assertEquals($expectedObj, $obj);
        }

        public function test_32_UrlFromObj_WithOptionsIfAvailable()
        {
            $obj = new UrlObj(
                baseUrl: "https://cdn.pixelbin.io",
                filePath: "__playground/playground-default.jpeg",
                pattern: "erase.bg(shadow:true)~t.merge(m:underlay,i:eU44YkFJOHlVMmZrWVRDOUNTRm1D,b:screen,r:true)",
                version: "v2",
                zone: "z-slug",
                cloudName: "red-scene-95b6ea",
                options: new UrlObjOptions(
                    dpr: 2.5,
                    f_auto: true
                ),
                worker: false,
                workerPath: "",
                transformations: [
                    new UrlTransformation()
                ]
            );
            $expectedUrl = "https://cdn.pixelbin.io/v2/red-scene-95b6ea/z-slug/original/__playground/playground-default.jpeg?dpr=2.5&f_auto=true";
            $url = Url::obj_to_url($obj);

            $this->assertEquals($expectedUrl, $url);
        }

        public function test_33_FailureWhileRetreivingObjFromUrlWithInvalidOptions()
        {
            $url = "https://cdn.pixelbin.io/v2/feel/erase.bg(shadow:true)~t.merge(m:underlay,i:eU44YkFJOHlVMmZrWVRDOUNTRm1D,b:screen,r:true)/MZZKB3e1hT48o0NYJ2Kxh.jpeg?dpr=5.5&f_auto=true";

            $this->expectException(Exceptions\PDKIllegalQueryParameterError::class);
            $this->expectExceptionMessage("DPR value should be numeric and should be between 0.1 to 5.0");
            Url::url_to_obj($url);
        }

        public function test_34_FailureWhileRetreivingUrlFromObjWithInvalidOptions()
        {
            $obj = new UrlObj(
                baseUrl: "https://cdn.pixelbin.io",
                filePath: "__playground/playground-default.jpeg",
                version: "v2",
                zone: "z-slug",
                cloudName: "red-scene-95b6ea",
                options: new UrlObjOptions(
                    dpr: 2.5,
                    f_auto: "abc"
                ),
                transformations: [
                    new UrlTransformation()
                ]
            );

            $this->expectException(Exceptions\PDKIllegalQueryParameterError::class);
            $this->expectExceptionMessage("F_auto value should be boolean");
            Url::obj_to_url($obj);
        }

        public function test_35_GetObjFromUrl4()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/dill-doe-36b4fc/t.rotate(a:102)~p:preset1(a:100,b:2.1,c:test)/__playground/playground-default.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "rotate",
                        values: [
                            ["key" => "a", "value" => "102"]
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1",
                        values: [
                            ["key" => "a", "value" => "100"],
                            ["key" => "b", "value" => "2.1"],
                            ["key" => "c", "value" => "test"]
                        ]
                    )
                ],
                cloudName: "dill-doe-36b4fc",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.rotate(a:102)~p:preset1(a:100,b:2.1,c:test)",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_36_GetObjFromUrl5()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/dill-doe-36b4fc/t.rotate(a:102)~p:preset1/__playground/playground-default.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "rotate",
                        values: [
                            ["key" => "a", "value" => "102"]
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1"
                    )
                ],
                cloudName: "dill-doe-36b4fc",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.rotate(a:102)~p:preset1",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_37_GetObjFromUrl6()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/dill-doe-36b4fc/t.rotate(a:102)~p:preset1()/__playground/playground-default.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "rotate",
                        values: [
                            ["key" => "a", "value" => "102"]
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1"
                    )
                ],
                cloudName: "dill-doe-36b4fc",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.rotate(a:102)~p:preset1()",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_38_GetObjFromUrl7()
        {
            $obj = Url::url_to_obj("https://cdn.pixelbin.io/v2/dill-doe-36b4fc/t.rotate(a:102)~p:preset1(a:12/__playground/playground-default.jpeg");
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "rotate",
                        values: [
                            ["key" => "a", "value" => "102"]
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1",
                        values: [
                            ["key" => "a", "value" => "12"]
                        ]
                    )
                ],
                cloudName: "dill-doe-36b4fc",
                zone: "",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "t.rotate(a:102)~p:preset1(a:12",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_39_UrlFromObj2()
        {
            $obj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize",
                        values: [
                            ["key" => "h", "value" => "200"],
                            ["key" => "w", "value" => "100"],
                            ["key" => "fill", "value" => "999"]
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "erase",
                        name: "bg"
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "extend"
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1",
                        values: [
                            ["key" => "a", "value" => "200"],
                            ["key" => "b", "value" => "1.2"],
                            ["key" => "c", "value" => "test"]
                        ]
                    )
                ],
                cloudName: "red-scene-95b6ea",
                zone: "z-slug",
                version: "v2",
                baseUrl: "https://cdn.pixelbin.io",
                filePath: "__playground/playground-default.jpeg"
            );

            $expectedUrl = "https://cdn.pixelbin.io/v2/red-scene-95b6ea/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()~p:preset1(a:200,b:1.2,c:test)/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);

            $obj->version = "v1";

            $expectedUrl = "https://cdn.pixelbin.io/v1/red-scene-95b6ea/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()~p:preset1(a:200,b:1.2,c:test)/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);
        }

        public function test_40_UrlFromObj3()
        {
            $obj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize",
                        values: [
                            ["key" => "h", "value" => "200"],
                            ["key" => "w", "value" => "100"],
                            ["key" => "fill", "value" => "999"]
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "erase",
                        name: "bg"
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "extend"
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1",
                        values: []
                    )
                ],
                cloudName: "red-scene-95b6ea",
                zone: "z-slug",
                version: "v2",
                baseUrl: "https://cdn.pixelbin.io",
                filePath: "__playground/playground-default.jpeg"
            );

            $expectedUrl = "https://cdn.pixelbin.io/v2/red-scene-95b6ea/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()~p:preset1/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);

            $obj->version = "v1";

            $expectedUrl = "https://cdn.pixelbin.io/v1/red-scene-95b6ea/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()~p:preset1/__playground/playground-default.jpeg";
            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);
        }

        public function test_41_ObjToUrlThenSameUrlToObj_Wrkr1()
        {
            $expectedUrl = "https://cdn.pixelbin.io/v2/red-scene-95b6ea/wrkr/image.jpeg";
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "red-scene-95b6ea",
                zone: "",
                protocol: 'https',
                host: 'cdn.pixelbin.io',
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "",
                filePath: "",
                version: "v2",
                worker: true,
                workerPath: "image.jpeg",
                options: new UrlObjOptions()
            );

            $obj = Url::url_to_obj($expectedUrl);
            $this->assertEquals($expectedObj, $obj);

            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);
        }

        public function test_42_ObjToUrlThenSameUrlToObj_Wrkr2()
        {
            $expectedUrl = "https://cdn.pixelbin.io/v2/falling-surf-7c8bb8/abcdef/wrkr/misc/general/free/original/images/favicon.ico";
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "falling-surf-7c8bb8",
                zone: "abcdef",
                protocol: "https",
                host: "cdn.pixelbin.io",
                baseUrl: "https://cdn.pixelbin.io",
                pattern: "",
                filePath: "",
                version: "v2",
                worker: true,
                workerPath: "misc/general/free/original/images/favicon.ico",
                options: new UrlObjOptions()
            );

            $obj = Url::url_to_obj($expectedUrl);
            $this->assertEquals($expectedObj, $obj);

            $url = Url::obj_to_url($obj);
            $this->assertEquals($expectedUrl, $url);
        }

        public function test_43_FailureForOptionDprQueryParam()
        {
            $obj = new UrlObj(
                baseUrl: "https://cdn.pixelbin.io",
                filePath: "__playground/playground-default.jpeg",
                version: "v2",
                zone: "z-slug",
                cloudName: "red-scene-95b6ea",
                options: new UrlObjOptions(dpr: 5.5, f_auto: true),
                transformations: [[]],
            );

            $this->expectException(Exceptions\PDKIllegalQueryParameterError::class);
            $this->expectExceptionMessage("DPR value should be numeric and should be between 0.1 to 5.0");
            Url::obj_to_url($obj);
        }

        public function test_44_FailureForOptionFautoQueryParam()
        {
            $obj = new UrlObj(
                baseUrl: "https://cdn.pixelbin.io",
                filePath: "__playground/playground-default.jpeg",
                version: "v2",
                zone: "z-slug",
                cloudName: "red-scene-95b6ea",
                options: new UrlObjOptions(dpr: 2.5, f_auto: "abc"),
                transformations: [[]],
            );

            $this->expectException(Exceptions\PDKIllegalQueryParameterError::class);
            Url::obj_to_url($obj);
        }
    }
}
