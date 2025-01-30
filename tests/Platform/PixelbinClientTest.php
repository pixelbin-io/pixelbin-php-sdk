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
    use Exception;
    use Pixelbin\Platform\Enums\AccessEnum;

    final class PixelbinClientTest extends TestCase
    {
        // switch to false to hit the PixelBin APIs while testing
        public bool $enableMocking = true;
        public array $config;
        public PixelbinConfig $pixelbinConfig;
        public PixelbinClient $pixelbinClient;
        public string $folder_name;
        public string $folder_path;
        public MockObject|GuzzleHttpHelper $guzzleHttpHelperMock;

        public function setMockObjectExpectations(array $mockResponse, array $calledWithArguments): void
        {
            if ($this->enableMocking) {
                $this->guzzleHttpHelperMock
                    ->expects($this->once())
                    ->method("request")
                    ->with(...$calledWithArguments)
                    ->willReturn($mockResponse);
                APIClient::$helper = $this->guzzleHttpHelperMock;
            }
        }

        public function checkUserAgentHeader(array $data): bool
        {
            $sdk = Utils::getSdkDetails();

            $userAgent = $sdk["name"] . "/" . $sdk["version"] . " (php)";
            $expected_value = [
                "host" => $this->equalTo(CONFIG["host"]),
                "Authorization" => $this->anything(),
                "User-Agent" => $this->stringEqualsStringIgnoringLineEndings($userAgent)
            ];

            if (array_key_exists("Content-Type", $data)) {
                $expected_value['Content-Type'] = $this->equalTo("application/json");
            }

            foreach ($expected_value as $key => $value) {
                try {
                    $this->assertArrayHasKey($key, $data);
                    $this->assertThat($data[$key], $expected_value[$key]);
                } catch (Exception $e) {
                    print_r($e->getMessage());
                    return false;
                }
            }
            return true;
        }

        public function setUpAssertions(array $mockData = [], array $realData = [])
        {
            if ($this->enableMocking)
                $this->assertEquals(...$realData);
        }

        public function setUp(): void
        {
            $this->config = CONFIG;
            $this->pixelbinConfig = new PixelbinConfig($this->config);
            $this->pixelbinClient = new PixelbinClient($this->pixelbinConfig);

            // Create Data
            $this->folder_name = "testdir";
            $this->folder_path = "/";

            $this->guzzleHttpHelperMock = $this->createMock(GuzzleHttpHelper::class);
        }

        public function test_createFolder(): void
        {
            $mock_response = MOCK_RESPONSE["createFolder"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "post",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/folders",
                    [],
                    $this->identicalTo(
                        [
                            "name" => "testdir",
                            "path" => "/"
                        ]
                    ),
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    })
                ]
            );

            $resp = $this->pixelbinClient->assets->createFolder(
                name: $this->folder_name,
                path: $this->folder_path
            );

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_FileUploadCase1()
        {
            $mock_response = MOCK_RESPONSE["fileUpload1"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "post",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/upload/direct",
                    [],
                    $this->anything(),
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $file = __DIR__ . "/1.jpeg";
            $resp = $this->pixelbinClient->assets->fileUpload(
                file: fopen($file, "r"),
            );

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_FileUploadCase2()
        {
            $file = __DIR__ . "/1.jpeg";
            $tags = ["tag1", "tag2"];

            $mock_response = MOCK_RESPONSE["fileUpload2"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "post",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/upload/direct",
                    [],
                    $this->anything(),
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->fileUpload(
                file: fopen($file, "r"),
                path: $this->folder_name,
                name: "1",
                access: AccessEnum::PUBLIC_READ,
                tags: $tags,
                metadata: (object) [],
                overwrite: false,
                filenameOverride: true,
            );

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_GetFileById()
        {
            $_id = "9d331030-b695-475e-9d4a-a660696d5fa5";
            $_id = "ea75fe6c-bba3-4706-85e0-af7bc5d73381";

            $mock_response = MOCK_RESPONSE["getFileById"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/files/id/$_id",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->getFileById(
                _id: $_id,
            );

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_ListFilesCase1()
        {
            $mock_response = MOCK_RESPONSE["listFiles1"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/listFiles",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->listFiles();

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_ListFilesCase2()
        {
            $mock_response = MOCK_RESPONSE["listFiles2"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/listFiles",
                    [
                        "name" => "1",
                        "path" => "testdir",
                        "format" => "jpeg",
                        "tags[0]" => "tag1",
                        "tags[1]" => "tag2",
                        "onlyFiles" => "true",
                        "onlyFolders" => "false",
                        "pageNo" => 1,
                        "pageSize" => 10,
                        "sort" => "name",
                    ],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->listFiles(
                name: "1",
                path: $this->folder_name,
                format: "jpeg",
                tags: ["tag1", "tag2"],
                onlyFiles: true,
                onlyFolders: false,
                pageNo: 1,
                pageSize: 10,
                sort: "name",
            );

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_UrlUpload()
        {
            $mock_data = [
                "url" => "https://www.fetchfind.com/blog/wp-content/uploads/2017/08/cat-2734999_1920-5-common-cat-sounds.jpg",
                "path" => $this->folder_name,
                "name" => "2",
                "access" => AccessEnum::PUBLIC_READ,
                "tags" => ["cat", "animal"],
                "metadata" => (object) [],
                "overwrite" => false,
                "filenameOverride" => true,
            ];
            $mock_response = MOCK_RESPONSE["urlUpload"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "post",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/upload/url",
                    [],
                    $mock_data,
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $tags = ["cat", "animal"];
            $url = "https://www.fetchfind.com/blog/wp-content/uploads/2017/08/cat-2734999_1920-5-common-cat-sounds.jpg";
            $resp = $this->pixelbinClient->assets->urlUpload(
                url: $url,
                path: $this->folder_name,
                name: "2",
                access: AccessEnum::PUBLIC_READ,
                tags: $tags,
                metadata: (object) [],
                overwrite: false,
                filenameOverride: true,
            );

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_CreateSignedUrlCase1()
        {
            $mock_response = MOCK_RESPONSE["createSignedURL1"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "post",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/upload/signed-url",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->createSignedUrl();

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_CreateSignedUrlCase2()
        {
            $mock_data = [
                "name" => "1",
                "path" => "testdir",
                "format" => "jpeg",
                "access" => AccessEnum::PUBLIC_READ,
                "tags" => ["tag1", "tag2"],
                "metadata" => (object) [],
                "overwrite" => false,
                "filenameOverride" => true,
            ];
            $mock_response = MOCK_RESPONSE["createSignedURL2"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "post",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/upload/signed-url",
                    [],
                    $mock_data,
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $data = [
                "name" => "1",
                "path" => $this->folder_name,
                "format" => "jpeg",
                "access" => AccessEnum::PUBLIC_READ,
                "tags" => ["tag1", "tag2"],
                "metadata" => (object) [],
                "overwrite" => false,
                "filenameOverride" => true,
            ];

            $resp = $this->pixelbinClient->assets->createSignedUrl(...$data);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_UpdateFileCase1()
        {
            $mock_data = [
                "name" => "1_"
            ];
            $mock_response = MOCK_RESPONSE["updateFile1"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "patch",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/files/1",
                    [],
                    $mock_data,
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $fileId = "1";
            $name = "1_";
            $data = [
                "name" => $name,
            ];

            $resp = $this->pixelbinClient->assets->updateFile($fileId, ...$data);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_UpdateFileCase2()
        {
            $mock_data = [
                "name" => $this->folder_name . "_",
                "path" => $this->folder_name,
                "access" => AccessEnum::PRIVATE ,
                "isActive" => true,
                "tags" => ["updated-tag1", "updated-tag2"],
                "metadata" => (object) ["key" => "value"],
            ];
            $mock_response = MOCK_RESPONSE["updateFile2"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "patch",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/files/$this->folder_name/1",
                    [],
                    $mock_data,
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $fileId = $this->folder_name . "/1";
            $name = $this->folder_name . "_";
            $tags = ["updated-tag1", "updated-tag2"];
            $data = [
                "name" => $name,
                "path" => $this->folder_name,
                "access" => AccessEnum::PRIVATE ,
                "isActive" => true,
                "tags" => $tags,
                "metadata" => (object) ["key" => "value"],
            ];

            $resp = $this->pixelbinClient->assets->updateFile($fileId, ...$data);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_GetFileByFileId()
        {
            $mock_response = MOCK_RESPONSE["getFileByFileId"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/files/$this->folder_name/2",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $fileId = $this->folder_name . "/2";

            $resp = $this->pixelbinClient->assets->getFileByFileId($fileId);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_DeleteFile()
        {
            $mock_response = MOCK_RESPONSE["deleteFile"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "delete",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/files/1_",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $fileId = "1_";

            $resp = $this->pixelbinClient->assets->deleteFile($fileId);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_DeleteFiles()
        {
            $_ids = [
                "745f0ab2-bf8e-4933-8acb-b526e74525d7",
                "e63ce0c2-3c56-4836-8e25-5e862cb156d8",
            ];
            $mock_data = [
                "ids" => $_ids
            ];
            $mock_response = MOCK_RESPONSE["deleteFiles"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "post",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/files/delete",
                    [],
                    $mock_data,
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $_ids = [
                "745f0ab2-bf8e-4933-8acb-b526e74525d7",
                "e63ce0c2-3c56-4836-8e25-5e862cb156d8",
            ];
            $data = [
                "ids" => $_ids,
            ];

            $mock_data = json_encode($data);

            $resp = $this->pixelbinClient->assets->deleteFiles($_ids);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_UpdateFolder()
        {
            $mock_data = [
                "isActive" => true
            ];
            $mock_response = MOCK_RESPONSE["updateFolder"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "patch",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/folders/testdir",
                    [],
                    $mock_data,
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $folderId = $this->folder_name;
            $resp = $this->pixelbinClient->assets->updateFolder($folderId, true);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_GetFolderDetails()
        {
            $mock_response = MOCK_RESPONSE["getFolderDetails"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/folders",
                    [
                        "name" => "testdir",
                        "path" => ""
                    ],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $name = $this->folder_name;
            $data = [
                "name" => $name,
            ];

            $resp = $this->pixelbinClient->assets->getFolderDetails("", $name);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_GetFolderAncestors()
        {
            $folder_id = "90e47275-1e7b-4e50-a314-3e2c9176c842";
            $mock_response = MOCK_RESPONSE["getFolderAncestors"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/folders/$folder_id/ancestors",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $folder_id = "90e47275-1e7b-4e50-a314-3e2c9176c842";

            $resp = $this->pixelbinClient->assets->getFolderAncestors($folder_id);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_DeleteFolder()
        {
            $folder_id = "90e47275-1e7b-4e50-a314-3e2c9176c842";
            $mock_response = MOCK_RESPONSE["deleteFolder"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "delete",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/folders/$folder_id",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $folder_id = "90e47275-1e7b-4e50-a314-3e2c9176c842";

            $resp = $this->pixelbinClient->assets->deleteFolder($folder_id);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_GetModules()
        {
            $mock_response = MOCK_RESPONSE["getModules"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/playground/plugins",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->getModules();

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_GetModule()
        {
            $mock_response = MOCK_RESPONSE["getModule"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/playground/plugins/erase",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->getModule(identifier: "erase");

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_AddCredentials()
        {
            $apiKey = MOCK_RESPONSE["updateCredentials"]["apiKey"];
            $mock_data = [
                "credentials" => (object) ["apiKey" => "$apiKey"],
                "pluginId" => "remove",
            ];
            $mock_response = MOCK_RESPONSE["addCredentials"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "post",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/credentials",
                    [],
                    $mock_data,
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $apiKey = MOCK_RESPONSE["updateCredentials"]["apiKey"];
            $mock_response = MOCK_RESPONSE["addCredentials"]["response"];

            $credentials = ["apiKey" => $apiKey];
            $pluginId = "remove";
            $resp = $this->pixelbinClient->assets->addCredentials((object) $credentials, $pluginId);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_UpdateCredentials()
        {
            $apiKey = MOCK_RESPONSE["updateCredentials"]["apiKey"];
            $mock_data = [
                "credentials" => (object) ["apiKey" => "$apiKey"],
            ];
            $mock_response = MOCK_RESPONSE["updateCredentials"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "patch",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/credentials/remove",
                    [],
                    $mock_data,
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $apiKey = MOCK_RESPONSE["updateCredentials"]["apiKey"];

            $credentials = ["apiKey" => $apiKey];
            $pluginId = "remove";
            $resp = $this->pixelbinClient->assets->updateCredentials($pluginId, (object) $credentials);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_DeleteCredentials()
        {
            $mock_response = MOCK_RESPONSE["deleteCredentials"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "delete",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/credentials/remove",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $pluginId = "remove";
            $resp = $this->pixelbinClient->assets->deleteCredentials($pluginId);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_AddPreset()
        {
            $mock_data = [
                "presetName" => "p1",
                "transformation" => "t.flip()~t.flop()",
                "params" => (object) [
                    "w" => ["type" => "integer", "default" => 200],
                    "h" => ["type" => "integer", "default" => 400],
                ],
            ];
            $mock_response = MOCK_RESPONSE["addPreset"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "post",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/presets",
                    [],
                    $mock_data,
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $presetName = "p1";
            $transformation = "t.flip()~t.flop()";
            $params = [
                "w" => ["type" => "integer", "default" => 200],
                "h" => ["type" => "integer", "default" => 400],
            ];

            $resp = $this->pixelbinClient->assets->addPreset(
                $presetName,
                $transformation,
                (object) $params
            );

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_GetPresets()
        {
            $mock_response = MOCK_RESPONSE["getPresets"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/presets",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->getPresets();

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_UpdatePresets()
        {
            $mock_data = [
                "archived" => true
            ];
            $mock_response = MOCK_RESPONSE["updatePresets"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "patch",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/presets/p1",
                    [],
                    $mock_data,
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->updatePreset(
                presetName: "p1",
                archived: true
            );

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_GetPreset()
        {
            $mock_response = MOCK_RESPONSE["getPreset"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/presets/p1",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->getPreset(presetName: "p1");

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_DeletePreset()
        {
            $mock_response = MOCK_RESPONSE["deletePreset"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "delete",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/presets/p1",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->deletePreset(presetName: "p1");

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_GetDefaultAssetForPlayground()
        {
            $mock_response = MOCK_RESPONSE["getDefaultAssetForPlayground"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/assets/v1.0/playground/default",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->assets->getDefaultAssetForPlayground();

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_CreateSignedUrlV2Case1()
        {
            $mock_data = [
                "name" => "1",
                "path" => "testdir",
                "format" => "jpeg",
                "access" => AccessEnum::PUBLIC_READ,
                "tags" => ["tag1", "tag2"],
                "metadata" => (object) [],
                "overwrite" => false,
                "filenameOverride" => true,
            ];
            $mock_response = MOCK_RESPONSE["createSignedUrlV2Case1"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "post",
                    CONFIG["domain"] . "/service/platform/assets/v2.0/upload/signed-url",
                    [],
                    $mock_data,
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $data = [
                "name" => "1",
                "path" => $this->folder_name,
                "format" => "jpeg",
                "access" => AccessEnum::PUBLIC_READ,
                "tags" => ["tag1", "tag2"],
                "metadata" => (object) [],
                "overwrite" => false,
                "filenameOverride" => true,
            ];
            $resp = $this->pixelbinClient->assets->createSignedUrlV2(...$data);

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }

        public function test_GetAppOrgDetails()
        {
            $mock_response = MOCK_RESPONSE["getAppOrgDetails"]["response"];
            $this->setMockObjectExpectations(
                $mock_response,
                [
                    "get",
                    CONFIG["domain"] . "/service/platform/organization/v1.0/apps/info",
                    [],
                    [],
                    self::callback(function ($data) {
                        return $this->checkUserAgentHeader($data);
                    }),
                ]
            );

            $resp = $this->pixelbinClient->organization->getAppOrgDetails();

            $this->setUpAssertions(
                [
                    $mock_response["content"],
                    $resp
                ],
                [
                    200,
                    $mock_response["status_code"]
                ]
            );
        }
    }
}
