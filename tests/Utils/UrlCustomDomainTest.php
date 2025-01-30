<?php

declare(strict_types=1);

namespace Pixelbin\Tests\Utils {

    use Pixelbin\Utils\Security;
    use Pixelbin\Utils\Url\UrlConfig;
    use Pixelbin\Utils\Url\UrlTransformation;
    require_once(__DIR__ . "/test_utils.php");

    use Pixelbin\Utils\{
        Url\Url,
        Url\UrlObj,
        Url\UrlObjOptions
    };
    use Pixelbin\Common\{
        Exceptions,
    };

    use PHPUnit\Framework\TestCase;
    use Exception;

    final class UrlCustomDomainTest extends TestCase
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

        public function test_1_ObjFromUrl_DefaultZoneWithTransformations_FileDepth1()
        {
            $url = "https://cdn.twist.vision/v2/t.resize()/playground-default.jpeg";
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize"
                    )
                ],
                cloudName: "",
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.resize()",
                version: "v2",
                filePath: "playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));

            $this->assertEquals($expectedObj, $obj);
        }

        public function test_2_ObjFromUrl_DefaultZoneWithTransformations_FileDepthGreaterThan1()
        {
            $url = "https://cdn.twist.vision/v2/t.resize()/test/__playground/playground-default.jpeg";
            $obj = URL::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize"
                    )
                ],
                cloudName: "",
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.resize()",
                version: "v2",
                filePath: "test/__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );

            $this->assertEquals($expectedObj, $obj);
        }

        public function test_3_ObjFromUrl_DefaultZoneWithTransformationsWithParam()
        {
            $url = "https://cdn.twist.vision/v2/t.rotate(a:102)/__playground/playground-default.jpeg";
            $obj = URL::url_to_obj($url, new UrlConfig(isCustomDomain: true));
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
                cloudName: "",
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.rotate(a:102)",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );

            $this->assertEquals($expectedObj, $obj);
        }

        public function test_4_ObjFromUrl_DefaultZoneWithTransformationsWithParams()
        {
            $url = "https://cdn.twist.vision/v2/t.rotate(a:102,b:200)/__playground/playground-default.jpeg";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "rotate",
                        values: [
                            ["key" => "a", "value" => "102"],
                            ["key" => "b", "value" => "200"]
                        ]
                    )
                ],
                cloudName: "",
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.rotate(a:102,b:200)",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );

            $this->assertEquals($expectedObj, $obj);
        }

        public function test_5_ObjFromUrlWithZoneSlug_FileDepthEquals1()
        {
            $url = "https://cdn.twist.vision/v2/zonesl/t.resize()/playground-default.jpeg";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize"
                    )
                ],
                cloudName: "",
                zone: "zonesl",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.resize()",
                version: "v2",
                filePath: "playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );

            $this->assertEquals($expectedObj, $obj);
        }

        public function test_6_ObjFromUrlWithZoneSlug_FileDepthGreaterThan1()
        {
            $url = "https://cdn.twist.vision/v2/zonesl/t.resize()/test/__playground/playground-default.jpeg";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "resize"
                    )
                ],
                cloudName: "",
                zone: "zonesl",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.resize()",
                version: "v2",
                filePath: "test/__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );

            $this->assertEquals($expectedObj, $obj);
        }

        public function test_7_ObjFromUrl_Error()
        {
            $url = "https://cdn.twist.vision/v2";
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Invalid pixelbin url. Please make sure the url is correct.");
            Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
        }

        public function test_8_ObjFromUrl_MultipleTransformations()
        {
            $url = "https://cdn.twist.vision/v2/t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()/__playground/playground-default.jpeg";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
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
                cloudName: "",
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_9_ObjFromUrlWithPreset()
        {
            $url = "https://cdn.twist.vision/v2/z-slug/t.compress()~t.resize()~t.extend()~p.apply(n:presetNameXyx)/alien_fig_tree_planet_x_wallingford_seattle_washington_usa_517559.jpeg";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
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
                cloudName: "",
                zone: "z-slug",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.compress()~t.resize()~t.extend()~p.apply(n:presetNameXyx)",
                filePath: "alien_fig_tree_planet_x_wallingford_seattle_washington_usa_517559.jpeg",
                version: "v2",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_10_ObjFromUrlWithPreset1()
        {
            $url = "https://cdn.twist.vision/v3/t.compress()~t.resize()~t.extend()~p.apply(n:presetNameXyx)/alien_fig_tree_planet_x_wallingford_seattle_washington_usa_517559.jpeg";
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Invalid pixelbin url. Please make sure the url is correct.");
            Url::url_to_obj($url);
        }

        public function test_11_HandleIncorrectUrls()
        {
            $url = "https://cdn.twist.vision//v2/original~original/__playground/playground-default.jpeg";
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Invalid pixelbin url. Please make sure the url is correct.");
            Url::url_to_obj($url);
        }

        public function test_12_HandleIncorrectUrls_IncorrectZone()
        {
            $url = "https://cdn.twist.vision/v2/test/t.compress()~t.resize()~t.extend()~p.apply(n:presetNameXyx)/alien_fig_tree_planet_x_wallingford_seattle_washington_usa_517559.jpeg";
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Error Processing url. Please check the url is correct");
            Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
        }

        public function test_13_HandleIncorrectUrls_IncorrectPattern()
        {
            $url = "https://cdn.pixelbin.io/v2/red-scene-95b6ea/t.compress~t.resize()~t.extend()~p.apply(n:presetNameXyx)/alien_fig_tree_planet_x_wallingford_seattle_washington_usa_517559.jpeg";
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Error Processing url. Please check the url is correct");
            Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
        }

        public function test_14_HandleIncorrectUrls_IncorrectPattern2()
        {
            $url = "https://cdn.twist.vision/v2/zonesls/t.resize()/__playground/playground-default.jpeg";
            $this->expectException(Exceptions\PDKInvalidUrlError::class);
            $this->expectExceptionMessage("Error Processing url. Please check the url is correct");
            Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
        }

        public function test_15_ObjFromUrl_DefaultZoneUrl_6CharacterLengthPathSegment()
        {
            $url = "https://cdn.twist.vision/v2/original/z0/orgs/33/skills/icons/Fynd_Platform_Commerce_Extension.png";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "",
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "original",
                filePath: "z0/orgs/33/skills/icons/Fynd_Platform_Commerce_Extension.png",
                version: "v2",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_16_ObjFromUrl_DefaultZoneUrl_PathSegmentHavingWrkr()
        {
            $url = "https://cdn.twist.vision/v2/original/z0/orgs/33/wrkr/icons/Fynd_Platform_Commerce_Extension.png";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "",
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "original",
                filePath: "z0/orgs/33/wrkr/icons/Fynd_Platform_Commerce_Extension.png",
                version: "v2",
                worker: false,
                workerPath: "",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_17_ObjFromUrl_WorkerPath_FullPathWithDepthEquals1()
        {
            $url = "https://cdn.twist.vision/v2/wrkr/image.jpeg";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "",
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "",
                filePath: "",
                version: "v2",
                worker: true,
                workerPath: "image.jpeg",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_18_ObjFromUrl_WorkerPath_FullPathWithDepthGreaterThan1()
        {
            $url = "https://cdn.twist.vision/v2/wrkr/misc/general/free/original/images/favicon.ico";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "",
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "",
                filePath: "",
                version: "v2",
                worker: true,
                workerPath: "misc/general/free/original/images/favicon.ico",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_19_ObjFromUrl_WorkerPathWithZone_FullPathWithDepthEquals1()
        {
            $url = "https://cdn.twist.vision/v2/fyndnp/wrkr/robots.txt";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "",
                zone: "fyndnp",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "",
                filePath: "",
                version: "v2",
                worker: true,
                workerPath: "robots.txt",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }


        // ref -> https://fynd-f7.sentry.io/issues/3515703764/?project=6193211&referrer=slack
        public function test_20_ObjFromUrl_WorkerPathWithZone_FullPathWithDepthGreaterThan1()
        {
            $url = "https://cdn.twist.vision/v2/fyprod/wrkr/misc/general/free/original/images/favicon.ico";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "",
                zone: "fyprod",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "",
                filePath: "",
                version: "v2",
                worker: true,
                workerPath: "misc/general/free/original/images/favicon.ico",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_21_ObjFromUrlWithOptionsIfAvailable()
        {
            $url = "https://cdn.twist.vision/v2/feelzz/erase.bg(shadow:true)~t.merge(m:underlay,i:eU44YkFJOHlVMmZrWVRDOUNTRm1D,b:screen,r:true)/MZZKB3e1hT48o0NYJ2Kxh.jpeg?dpr=2.5&f_auto=true";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                protocol: "https",
                host: "cdn.twist.vision",
                search: "dpr=2.5&f_auto=true",
                baseUrl: "https://cdn.twist.vision",
                filePath: "MZZKB3e1hT48o0NYJ2Kxh.jpeg",
                pattern: "erase.bg(shadow:true)~t.merge(m:underlay,i:eU44YkFJOHlVMmZrWVRDOUNTRm1D,b:screen,r:true)",
                version: "v2",
                cloudName: "",
                zone: "feelzz",
                options: new UrlObjOptions(
                    dpr: 2.5,
                    f_auto: true
                ),
                transformations: [
                    new UrlTransformation(
                        plugin: "erase",
                        name: "bg",
                        values: [
                            ["key" => "shadow", "value" => "true"],
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "t",
                        name: "merge",
                        values: [
                            ["key" => "m", "value" => "underlay"],
                            ["key" => "i", "value" => "eU44YkFJOHlVMmZrWVRDOUNTRm1D"],
                            ["key" => "b", "value" => "screen"],
                            ["key" => "r", "value" => "true"],
                        ]
                    )
                ],
                worker: false,
                workerPath: ""
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_22_GetFailureWhileRetrievingObjFromUrlWithInvalidOptions()
        {
            $url = "https://cdn.twist.vision/v2/erase.bg(shadow:true)~t.merge(m:underlay,i:eU44YkFJOHlVMmZrWVRDOUNTRm1D,b:screen,r:true)/MZZKB3e1hT48o0NYJ2Kxh.jpeg?dpr=5.5&f_auto=true";
            $this->expectException(Exceptions\PDKIllegalQueryParameterError::class);
            $this->expectExceptionMessage("DPR value should be numeric and should be between 0.1 to 5.0");
            Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
        }

        public function test_23_GenerateUrlFromObjWithOptionsIfAvailable()
        {
            $obj = new UrlObj(
                baseUrl: "https://cdn.twist.vision",
                isCustomDomain: true,
                filePath: "__playground/playground-default.jpeg",
                version: "v2",
                zone: "z-slug",
                options: new UrlObjOptions(dpr: 2.5, f_auto: true),
                transformations: [new UrlTransformation()]
            );
            $generatedUrl = Url::obj_to_url($obj);
            $this->assertEquals("https://cdn.twist.vision/v2/z-slug/original/__playground/playground-default.jpeg?dpr=2.5&f_auto=true", $generatedUrl);
        }

        public function test_24_GetFailureWhileRetrievingUrlFromObjWithCloudname()
        {
            $obj = new UrlObj(
                baseUrl: "https://cdn.twist.vision",
                isCustomDomain: true,
                filePath: "__playground/playground-default.jpeg",
                version: "v2",
                zone: "z-slug",
                cloudName: "red-scene-95b6ea",
                options: new UrlObjOptions(dpr: 2.5, f_auto: true),
                transformations: [new UrlTransformation()]
            );
            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("key cloudName is not valid for custom domains");
            Url::obj_to_url($obj);
        }

        public function test_25_GetFailureWhileRetrievingUrlFromObjWithInvalidOptions()
        {
            $obj = new UrlObj(
                baseUrl: "https://cdn.twist.vision",
                isCustomDomain: true,
                filePath: "__playground/playground-default.jpeg",
                version: "v2",
                zone: "z-slug",
                options: new UrlObjOptions(dpr: 2.5, f_auto: "abc"),
                transformations: [new UrlTransformation()]
            );
            $this->expectException(Exceptions\PDKIllegalQueryParameterError::class);
            $this->expectExceptionMessage("F_auto value should be boolean");
            Url::obj_to_url($obj);
        }

        public function test_26_ObjFromUrl1()
        {
            $url = "https://cdn.twist.vision/v2/t.rotate(a:102)~p:preset1(a:100,b:2.1,c:test)/__playground/playground-default.jpeg";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "rotate",
                        values: [
                            ["key" => "a", "value" => "102"],
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
                    ),
                ],
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.rotate(a:102)~p:preset1(a:100,b:2.1,c:test)",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_27_ObjFromUrl2()
        {
            $url = "https://cdn.twist.vision/v2/t.rotate(a:102)~p:preset1/__playground/playground-default.jpeg";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "rotate",
                        values: [
                            ["key" => "a", "value" => "102"],
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1"
                    ),
                ],
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.rotate(a:102)~p:preset1",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_28_ObjFromUrl3()
        {
            $url = "https://cdn.twist.vision/v2/t.rotate(a:102)~p:preset1()/__playground/playground-default.jpeg";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "rotate",
                        values: [
                            ["key" => "a", "value" => "102"],
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1"
                    ),
                ],
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.rotate(a:102)~p:preset1()",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_29_ObjFromUrl4()
        {
            $url = "https://cdn.twist.vision/v2/t.rotate(a:102)~p:preset1(a:12/__playground/playground-default.jpeg";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [
                    new UrlTransformation(
                        plugin: "t",
                        name: "rotate",
                        values: [
                            ["key" => "a", "value" => "102"],
                        ]
                    ),
                    new UrlTransformation(
                        plugin: "p",
                        name: "preset1",
                        values: [
                            ["key" => "a", "value" => "12"],
                        ]
                    ),
                ],
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "t.rotate(a:102)~p:preset1(a:12",
                version: "v2",
                filePath: "__playground/playground-default.jpeg",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
        }

        public function test_30_GenerateUrlFromObj()
        {
            $transformations = [
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
                ),
            ];
            $obj = new UrlObj(
                isCustomDomain: true,
                zone: "z-slug",
                version: "v2",
                transformations: $transformations,
                baseUrl: "https://cdn.twist.vision",
                filePath: "__playground/playground-default.jpeg",
                options: new UrlObjOptions()
            );
            $generatedUrl = Url::obj_to_url($obj);
            $this->assertEquals("https://cdn.twist.vision/v2/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()~p:preset1/__playground/playground-default.jpeg", $generatedUrl);
        }

        public function test_31_GenerateUrlFromObj1()
        {
            $transformations = [
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
                ),
            ];
            $obj = new UrlObj(
                isCustomDomain: true,
                zone: "z-slug",
                version: "v2",
                transformations: $transformations,
                baseUrl: "https://cdn.twist.vision",
                filePath: "__playground/playground-default.jpeg"
            );
            $generatedUrl = Url::obj_to_url($obj);
            $this->assertEquals("https://cdn.twist.vision/v2/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg(i:general)~t.extend()~p:preset1/__playground/playground-default.jpeg", $generatedUrl);
        }

        public function test_32_ThrowErrorIfWorkerIsTrueButWorkerPathIsNotDefined()
        {
            $transformations = [new UrlTransformation()];
            $obj = new UrlObj(
                isCustomDomain: true,
                zone: "z-slug",
                version: "v2",
                transformations: $transformations,
                baseUrl: "https://cdn.twist.vision",
                filePath: "__playground/playground-default.jpeg",
                worker: true
            );
            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("key workerPath should be defined");
            Url::obj_to_url($obj);
        }

        public function test_33_GenerateUrlFromObj_Empty()
        {
            $transformations = [new UrlTransformation()];
            $obj = new UrlObj(
                isCustomDomain: true,
                zone: "z-slu1",
                version: "v2",
                transformations: $transformations,
                baseUrl: "https://cdn.twist.vision",
                filePath: "__playground/playground-default.jpeg"
            );
            $generatedUrl = Url::obj_to_url($obj);
            $this->assertEquals("https://cdn.twist.vision/v2/z-slu1/original/__playground/playground-default.jpeg", $generatedUrl);
        }

        public function test_34_GenerateUrlFromObj_Undefined()
        {
            $obj = new UrlObj(
                isCustomDomain: true,
                zone: "z-slug",
                version: "v2",
                baseUrl: "https://cdn.twist.vision",
                filePath: "__playground/playground-default.jpeg"
            );
            $generatedUrl = Url::obj_to_url($obj);
            $this->assertEquals("https://cdn.twist.vision/v2/z-slug/original/__playground/playground-default.jpeg", $generatedUrl);
        }

        public function test_35_GenerateUrlFromObj_EmptyObj()
        {
            $obj = new UrlObj(
                isCustomDomain: true,
                zone: "z-slug",
                version: "v2",
                transformations: [new UrlTransformation()],
                baseUrl: "https://cdn.twist.vision",
                filePath: "__playground/playground-default.jpeg"
            );
            $generatedUrl = Url::obj_to_url($obj);
            $this->assertEquals("https://cdn.twist.vision/v2/z-slug/original/__playground/playground-default.jpeg", $generatedUrl);
        }

        public function test_36_GenerateUrlFromObj_EmptyObj1()
        {
            $obj = new UrlObj(
                isCustomDomain: true,
                zone: "z-slug",
                version: "v2",
                transformations: [new UrlTransformation()],
                baseUrl: "https://cdn.twist.vision",
                filePath: "__playground/playground-default.jpeg"
            );
            $generatedUrl = Url::obj_to_url($obj);
            $this->assertEquals("https://cdn.twist.vision/v2/z-slug/original/__playground/playground-default.jpeg", $generatedUrl);
        }

        public function test_37_ThrowErrorToGenerateUrlFromObjIfFilePathNotDefined()
        {
            $obj = new UrlObj(
                isCustomDomain: true,
                zone: "z-slug",
                version: "v2",
                transformations: [new UrlTransformation()],
                baseUrl: "https://cdn.twist.vision",
                filePath: ""
            );
            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("key filePath should be defined");
            Url::obj_to_url($obj);
        }

        public function test_38_GenerateUrlFromObj2()
        {
            $transformations = [
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
                    name: "preset1",
                    values: [
                        ["key" => "a", "value" => "200"],
                        ["key" => "b", "value" => "1.2"],
                        ["key" => "c", "value" => "test"],
                    ]
                )
            ];
            $obj = new UrlObj(
                isCustomDomain: true,
                zone: "z-slug",
                version: "v2",
                transformations: $transformations,
                baseUrl: "https://cdn.twist.vision",
                filePath: "__playground/playground-default.jpeg"
            );
            $generatedUrl = Url::obj_to_url($obj);
            $this->assertEquals("https://cdn.twist.vision/v2/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()~p:preset1(a:200,b:1.2,c:test)/__playground/playground-default.jpeg", $generatedUrl);
        }

        public function test_39_GenerateUrlFromObj3()
        {
            $transformations = [
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
                    name: "preset1",
                    values: []
                ),
            ];
            $obj = new UrlObj(
                isCustomDomain: true,
                zone: "z-slug",
                version: "v2",
                transformations: $transformations,
                baseUrl: "https://cdn.twist.vision",
                filePath: "__playground/playground-default.jpeg"
            );
            $generatedUrl = Url::obj_to_url($obj);
            $this->assertEquals("https://cdn.twist.vision/v2/z-slug/t.resize(h:200,w:100,fill:999)~erase.bg()~t.extend()~p:preset1/__playground/playground-default.jpeg", $generatedUrl);
        }

        public function test_40_ObjToUrlThenSameUrlToObj_Wrkr1()
        {
            $url = "https://cdn.twist.vision/v2/wrkr/image.jpeg";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "",
                zone: "",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "",
                filePath: "",
                version: "v2",
                worker: true,
                workerPath: "image.jpeg",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
            $obj->isCustomDomain = true;
            $generatedUrl = Url::obj_to_url($obj);
            $this->assertEquals($url, $generatedUrl);
        }

        public function test_41_ObjToUrlThenSameUrlToObj_Wrkr2()
        {
            $url = "https://cdn.twist.vision/v2/abcdef/wrkr/misc/general/free/original/images/favicon.ico";
            $obj = Url::url_to_obj($url, new UrlConfig(isCustomDomain: true));
            $expectedObj = new UrlObj(
                transformations: [],
                cloudName: "",
                zone: "abcdef",
                protocol: "https",
                host: "cdn.twist.vision",
                baseUrl: "https://cdn.twist.vision",
                pattern: "",
                filePath: "",
                version: "v2",
                worker: true,
                workerPath: "misc/general/free/original/images/favicon.ico",
                options: new UrlObjOptions()
            );
            $this->assertEquals($expectedObj, $obj);
            $obj->isCustomDomain = true;
            $generatedUrl = Url::obj_to_url($obj);
            $this->assertEquals($url, $generatedUrl);
        }

        public function test_42_SignUrl()
        {
            $signedURL = Security::signURL(
                "https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg",
                20,
                "459337ed-f378-4ddf-bad7-d7a4555c4572",
                "dummy-token"
            );

            $signedUrlObj = parse_url($signedURL);

            parse_str($signedUrlObj['query'], $searchParams);

            $keys = ["pbs", "pbe", "pbt"];

            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $searchParams);
                $this->assertNotNull($searchParams[$key]);
            }
        }

        public function test_43_SignUrlWithQuery()
        {
            $signedURL = Security::signURL(
                "https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg?testquery1=testval&testquery2=testval",
                20,
                "459337ed-f378-4ddf-bad7-d7a4555c4572",
                "dummy-token"
            );

            $signedUrlObj = parse_url($signedURL);
            parse_str($signedUrlObj['query'], $searchParams);

            $keys = ["pbs", "pbe", "pbt", "testquery1", "testquery2"];

            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $searchParams);
                $this->assertNotNull($searchParams[$key]);
                if (strpos($key, "testquery") !== false) {
                    $this->assertEquals("testval", $searchParams[$key]);
                }
            }
        }

        public function test_44_SignUrlCustomDomain()
        {
            $signedURL = Security::signURL(
                "https://krit.imagebin.io/v2/original/__playground/playground-default.jpeg",
                20,
                "08040485-dc83-450b-9e1f-f1040044ae3f",
                "dummy-token-2"
            );

            $signedUrlObj = parse_url($signedURL);
            parse_str($signedUrlObj['query'], $searchParams);

            $keys = ["pbs", "pbe", "pbt"];

            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $searchParams);
                $this->assertNotNull($searchParams[$key]);
            }
        }

        public function test_45_SignUrlFailureWhenEmptyUrlProvided()
        {
            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("url, accessKey, token & expirySeconds are required for generating signed URL");
            Security::signURL("", 20, "1", "dummy-token");
        }

        public function test_46_SignUrlFailureWhenEmptyAccessKeyProvided()
        {
            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("url, accessKey, token & expirySeconds are required for generating signed URL");
            Security::signURL("https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg", 20, "", "dummy-token");
        }

        public function test_47_SignUrlFailureWhenEmptyTokenProvided()
        {
            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            $this->expectExceptionMessage("url, accessKey, token & expirySeconds are required for generating signed URL");
            Security::signURL("https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg", 20, "1", "");
        }
    }
}
