<?php

namespace Pixelbin\Platform {
    use Pixelbin\Platform\Enums\AccessEnum;
    use Pixelbin\Common\Exceptions;
    use Pixelbin\Platform\Uploader;

    /**
     * PixelbinClient is a wrapper class for hitting pixelbin apis
     */
    class PixelbinClient
    {
        public PixelbinConfig $config;
        public ASSETS $assets;
        public ORGANIZATION $organization;
        public Uploader $uploader;

        public function __construct(PixelbinConfig $config)
        {
            $this->config = $config;
            $this->assets = new ASSETS($config);
            $this->organization = new ORGANIZATION($config);
            $this->uploader = new Uploader($this->assets);
        }
    }

    class Assets
    {
        private PixelbinConfig $config;

        public function __construct(PixelbinConfig $config)
        {
            $this->config = $config;
        }

        /**
         * Add credentials for a transformation module.
         *
         * Add a transformation modules's credentials for an organization.
         * @param object $credentials Credentials of the plugin
         * @param string $pluginId Unique identifier for the plugin this credential belongs to
         */
        public function addCredentials(
            object|null $credentials = null,
            string|null $pluginId = null
        ): array {
            $payload = [];

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($credentials !== null) {
                $body["credentials"] = $credentials;
            }

            if ($pluginId !== null) {
                $body["pluginId"] = $pluginId;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "post",
                url: "/service/platform/assets/v1.0/credentials",
                query: $query_params,
                body: $body,
                contentType: "application/json"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Update credentials of a transformation module.
         *
         * Update credentials of a transformation module, for an organization.
         * @param string $pluginId ID of the plugin whose credentials are being updated
         * @param object $credentials Credentials of the plugin
         */
        public function updateCredentials(
            string|null $pluginId = null,
            object|null $credentials = null
        ): array {
            $payload = [];

            if ($pluginId !== null) {
                $payload["pluginId"] = $pluginId;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($credentials !== null) {
                $body["credentials"] = $credentials;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "patch",
                url: "/service/platform/assets/v1.0/credentials/$pluginId",
                query: $query_params,
                body: $body,
                contentType: "application/json"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Delete credentials of a transformation module.
         *
         * Delete credentials of a transformation module, for an organization.
         * @param string $pluginId ID of the plugin whose credentials are being deleted
         */
        public function deleteCredentials(
            string|null $pluginId = null
        ): array {
            $payload = [];

            if ($pluginId !== null) {
                $payload["pluginId"] = $pluginId;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "delete",
                url: "/service/platform/assets/v1.0/credentials/$pluginId",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Get file details with _id
         *
         *
         * @param string $id _id of File
         */
        public function getFileById(
            string|null $_id = null
        ): array {
            $payload = [];

            if ($_id !== null) {
                $payload["_id"] = $_id;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "get",
                url: "/service/platform/assets/v1.0/files/id/$_id",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Get file details with fileId
         *
         *
         * @param string $fileId Combination of `path` and `name` of file
         */
        public function getFileByFileId(
            string|null $fileId = null
        ): array {
            $payload = [];

            if ($fileId !== null) {
                $payload["fileId"] = $fileId;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "get",
                url: "/service/platform/assets/v1.0/files/$fileId",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Update file details
         *
         *
         * @param string $fileId Combination of `path` and `name`
         * @param string $name Name of the file
         * @param string $path Path of the file
         * @param AccessEnum $access Access level of asset, can be either `public-read` or `private`
         * @param bool $isActive Whether the file is active
         * @param array $tags Tags associated with the file
         * @param object $metadata Metadata associated with the file
         */
        public function updateFile(
            string|null $fileId = null,
            string|null $name = null,
            string|null $path = null,
            AccessEnum|null $access = null,
            bool|null $isActive = null,
            array|null $tags = null,
            object|null $metadata = null
        ): array {
            $payload = [];

            if ($fileId !== null) {
                $payload["fileId"] = $fileId;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($name !== null) {
                $body["name"] = $name;
            }

            if ($path !== null) {
                $body["path"] = $path;
            }

            if ($access !== null) {
                $body["access"] = $access;
            }

            if ($isActive !== null) {
                $body["isActive"] = $isActive;
            }

            if ($tags !== null) {
                $body["tags"] = $tags;
            }

            if ($metadata !== null) {
                $body["metadata"] = $metadata;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "patch",
                url: "/service/platform/assets/v1.0/files/$fileId",
                query: $query_params,
                body: $body,
                contentType: "application/json"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Delete file
         *
         *
         * @param string $fileId Combination of `path` and `name`
         */
        public function deleteFile(
            string|null $fileId = null
        ): array {
            $payload = [];

            if ($fileId !== null) {
                $payload["fileId"] = $fileId;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "delete",
                url: "/service/platform/assets/v1.0/files/$fileId",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Delete multiple files
         *
         *
         * @param array $ids Array of file _ids to delete
         */
        public function deleteFiles(
            array|null $ids = null
        ): array {
            $payload = [];

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($ids !== null) {
                $body["ids"] = $ids;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "post",
                url: "/service/platform/assets/v1.0/files/delete",
                query: $query_params,
                body: $body,
                contentType: "application/json"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Create folder
         *
         * Create a new folder at the specified path. Also creates the ancestors if they do not exist.
         * @param string $name Name of the folder
         * @param string $path Path of the folder
         */
        public function createFolder(
            string|null $name = null,
            string|null $path = null
        ): array {
            $payload = [];

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($name !== null) {
                $body["name"] = $name;
            }

            if ($path !== null) {
                $body["path"] = $path;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "post",
                url: "/service/platform/assets/v1.0/folders",
                query: $query_params,
                body: $body,
                contentType: "application/json"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Get folder details
         *
         * Get folder details
         * @param string|null $path Optional. Folder path
         * @param string|null $name Optional. Folder name
         */
        public function getFolderDetails(
            string|null $path = null,
            string|null $name = null
        ): array {
            $payload = [];

            if ($path !== null) {
                $payload["path"] = $path;
            }

            if ($name !== null) {
                $payload["name"] = $name;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            if ($path !== null) {
                $query_params["path"] = $path;
            }

            if ($name !== null) {
                $query_params["name"] = $name;
            }

            $response = APIClient::execute(
                conf: $this->config,
                method: "get",
                url: "/service/platform/assets/v1.0/folders",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Update folder details
         *
         * Update folder details. Eg: Soft delete it by making `isActive` as `false`. We currently do not support updating folder name or path.
         * @param string $folderId combination of `path` and `name`
         * @param bool $isActive whether the folder is active
         */
        public function updateFolder(
            string|null $folderId = null,
            bool|null $isActive = null
        ): array {
            $payload = [];

            if ($folderId !== null) {
                $payload["folderId"] = $folderId;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($isActive !== null) {
                $body["isActive"] = $isActive;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "patch",
                url: "/service/platform/assets/v1.0/folders/$folderId",
                query: $query_params,
                body: $body,
                contentType: "application/json"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Delete folder
         *
         * Delete folder and all its children permanently.
         * @param string $id _id of folder to be deleted
         */
        public function deleteFolder(
            string|null $_id = null
        ): array {
            $payload = [];

            if ($_id !== null) {
                $payload["_id"] = $_id;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "delete",
                url: "/service/platform/assets/v1.0/folders/$_id",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Get all ancestors of a folder
         *
         * Get all ancestors of a folder, using the folder ID.
         * @param string $id _id of the folder
         */
        public function getFolderAncestors(
            string|null $_id = null
        ): array {
            $payload = [];

            if ($_id !== null) {
                $payload["_id"] = $_id;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "get",
                url: "/service/platform/assets/v1.0/folders/$_id/ancestors",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * List and search files and folders.
         *
         * List all files and folders in root folder. Search for files if name is provided. If path is provided, search in the specified path.
         * @param string|null $name Optional. Find items with matching name
         * @param string|null $path Optional. Find items with matching path
         * @param string|null $format Optional. Find items with matching format
         * @param array|null $tags Optional. Find items containing these tags
         * @param bool|null $onlyFiles Optional. If true will fetch only files
         * @param bool|null $onlyFolders Optional. If true will fetch only folders
         * @param int|null $pageNo Optional. Page No.
         * @param int|null $pageSize Optional. Page Size
         * @param string|null $sort Optional. Key to sort results by. A "-" suffix will sort results in descending orders.

         */
        public function listFiles(
            string|null $name = null,
            string|null $path = null,
            string|null $format = null,
            array|null $tags = null,
            bool|null $onlyFiles = null,
            bool|null $onlyFolders = null,
            int|null $pageNo = null,
            int|null $pageSize = null,
            string|null $sort = null
        ): array {
            $payload = [];

            if ($name !== null) {
                $payload["name"] = $name;
            }

            if ($path !== null) {
                $payload["path"] = $path;
            }

            if ($format !== null) {
                $payload["format"] = $format;
            }

            if ($tags !== null) {
                $payload["tags"] = $tags;
            }

            if ($onlyFiles !== null) {
                $payload["onlyFiles"] = $onlyFiles;
            }

            if ($onlyFolders !== null) {
                $payload["onlyFolders"] = $onlyFolders;
            }

            if ($pageNo !== null) {
                $payload["pageNo"] = $pageNo;
            }

            if ($pageSize !== null) {
                $payload["pageSize"] = $pageSize;
            }

            if ($sort !== null) {
                $payload["sort"] = $sort;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            if ($name !== null) {
                $query_params["name"] = $name;
            }

            if ($path !== null) {
                $query_params["path"] = $path;
            }

            if ($format !== null) {
                $query_params["format"] = $format;
            }

            if ($tags !== null) {
                $query_params["tags"] = $tags;
            }

            if ($onlyFiles !== null) {
                $query_params["onlyFiles"] = $onlyFiles;
            }

            if ($onlyFolders !== null) {
                $query_params["onlyFolders"] = $onlyFolders;
            }

            if ($pageNo !== null) {
                $query_params["pageNo"] = $pageNo;
            }

            if ($pageSize !== null) {
                $query_params["pageSize"] = $pageSize;
            }

            if ($sort !== null) {
                $query_params["sort"] = $sort;
            }

            $response = APIClient::execute(
                conf: $this->config,
                method: "get",
                url: "/service/platform/assets/v1.0/listFiles",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Get default asset for playground
         *
         * Get default asset for playground
         */
        public function getDefaultAssetForPlayground(): array
        {
            $payload = [];

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "get",
                url: "/service/platform/assets/v1.0/playground/default",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Get all transformation modules
         *
         * Get all transformation modules.
         */
        public function getModules(): array
        {
            $payload = [];

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "get",
                url: "/service/platform/assets/v1.0/playground/plugins",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Get Transformation Module by module identifier
         *
         * Get Transformation Module by module identifier
         * @param string $identifier identifier of Transformation Module
         */
        public function getModule(
            string|null $identifier = null
        ): array {
            $payload = [];

            if ($identifier !== null) {
                $payload["identifier"] = $identifier;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "get",
                url: "/service/platform/assets/v1.0/playground/plugins/$identifier",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Add a preset.
         *
         * Add a preset for an organization.
         * @param string $presetName Name of the preset
         * @param string $transformation A chain of transformations, separated by `~`
         * @param object $params Parameters object for transformation variables
         */
        public function addPreset(
            string|null $presetName = null,
            string|null $transformation = null,
            object|null $params = null
        ): array {
            $payload = [];

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($presetName !== null) {
                $body["presetName"] = $presetName;
            }

            if ($transformation !== null) {
                $body["transformation"] = $transformation;
            }

            if ($params !== null) {
                $body["params"] = $params;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "post",
                url: "/service/platform/assets/v1.0/presets",
                query: $query_params,
                body: $body,
                contentType: "application/json"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Get presets for an organization
         *
         * Retrieve presets for a specific organization.
         * @param int|null $pageNo Optional. Page number
         * @param int|null $pageSize Optional. Page size
         * @param string|null $name Optional. Preset name
         * @param string|null $transformation Optional. Transformation applied
         * @param bool|null $archived Optional. Indicates whether the preset is archived or not
         * @param array|null $sort Optional. Sort the results by a specific key
         */
        public function getPresets(
            int|null $pageNo = null,
            int|null $pageSize = null,
            string|null $name = null,
            string|null $transformation = null,
            bool|null $archived = null,
            array|null $sort = null
        ): array {
            $payload = [];

            if ($pageNo !== null) {
                $payload["pageNo"] = $pageNo;
            }

            if ($pageSize !== null) {
                $payload["pageSize"] = $pageSize;
            }

            if ($name !== null) {
                $payload["name"] = $name;
            }

            if ($transformation !== null) {
                $payload["transformation"] = $transformation;
            }

            if ($archived !== null) {
                $payload["archived"] = $archived;
            }

            if ($sort !== null) {
                $payload["sort"] = $sort;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            if ($pageNo !== null) {
                $query_params["pageNo"] = $pageNo;
            }

            if ($pageSize !== null) {
                $query_params["pageSize"] = $pageSize;
            }

            if ($name !== null) {
                $query_params["name"] = $name;
            }

            if ($transformation !== null) {
                $query_params["transformation"] = $transformation;
            }

            if ($archived !== null) {
                $query_params["archived"] = $archived;
            }

            if ($sort !== null) {
                $query_params["sort"] = $sort;
            }

            $response = APIClient::execute(
                conf: $this->config,
                method: "get",
                url: "/service/platform/assets/v1.0/presets",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Update a preset.
         *
         * Update a preset of an organization.
         * @param string $presetName Name of the preset to be updated
         * @param bool $archived Indicates if the preset has been archived
         */
        public function updatePreset(
            string|null $presetName = null,
            bool|null $archived = null
        ): array {
            $payload = [];

            if ($presetName !== null) {
                $payload["presetName"] = $presetName;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($archived !== null) {
                $body["archived"] = $archived;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "patch",
                url: "/service/platform/assets/v1.0/presets/$presetName",
                query: $query_params,
                body: $body,
                contentType: "application/json"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Delete a preset.
         *
         * Delete a preset of an organization.
         * @param string $presetName Name of the preset to be deleted
         */
        public function deletePreset(
            string|null $presetName = null
        ): array {
            $payload = [];

            if ($presetName !== null) {
                $payload["presetName"] = $presetName;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "delete",
                url: "/service/platform/assets/v1.0/presets/$presetName",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Get a preset.
         *
         * Get a preset of an organization.
         * @param string $presetName Name of the preset to be fetched
         */
        public function getPreset(
            string|null $presetName = null
        ): array {
            $payload = [];

            if ($presetName !== null) {
                $payload["presetName"] = $presetName;
            }

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "get",
                url: "/service/platform/assets/v1.0/presets/$presetName",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Upload File
         *
         * Upload File to Pixelbin
         * @param resource $file Asset file
         * @param string $path Path where you want to store the asset
         * @param string $name Name of the asset, if not provided name of the file will be used. Note - The provided name will be slugified to make it URL safe
         * @param AccessEnum $access Access level of asset, can be either `public-read` or `private`
         * @param array $tags Asset tags
         * @param object $metadata Asset related metadata
         * @param bool $overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
         * @param bool $filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
         */
        public function fileUpload(
            mixed $file = null,
            string|null $path = null,
            string|null $name = null,
            AccessEnum|null $access = null,
            array|null $tags = null,
            object|null $metadata = null,
            bool|null $overwrite = null,
            bool|null $filenameOverride = null
        ): array {
            $payload = [];

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($file !== null) {
                $body["file"] = $file;
            }

            if ($path !== null) {
                $body["path"] = $path;
            }

            if ($name !== null) {
                $body["name"] = $name;
            }

            if ($access !== null) {
                $body["access"] = $access;
            }

            if ($tags !== null) {
                $body["tags"] = $tags;
            }

            if ($metadata !== null) {
                $body["metadata"] = $metadata;
            }

            if ($overwrite !== null) {
                $body["overwrite"] = $overwrite;
            }

            if ($filenameOverride !== null) {
                $body["filenameOverride"] = $filenameOverride;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "post",
                url: "/service/platform/assets/v1.0/upload/direct",
                query: $query_params,
                body: $body,
                contentType: "multipart/form-data"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Upload Asset with url
         *
         * Upload Asset with url
         * @param string $url Asset URL
         * @param string $path Path where you want to store the asset
         * @param string $name Name of the asset, if not provided name of the file will be used. Note - The provided name will be slugified to make it URL safe
         * @param AccessEnum $access Access level of asset, can be either `public-read` or `private`
         * @param array $tags Asset tags
         * @param object $metadata Asset related metadata
         * @param bool $overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
         * @param bool $filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
         */
        public function urlUpload(
            string|null $url = null,
            string|null $path = null,
            string|null $name = null,
            AccessEnum|null $access = null,
            array|null $tags = null,
            object|null $metadata = null,
            bool|null $overwrite = null,
            bool|null $filenameOverride = null
        ): array {
            $payload = [];

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($url !== null) {
                $body["url"] = $url;
            }

            if ($path !== null) {
                $body["path"] = $path;
            }

            if ($name !== null) {
                $body["name"] = $name;
            }

            if ($access !== null) {
                $body["access"] = $access;
            }

            if ($tags !== null) {
                $body["tags"] = $tags;
            }

            if ($metadata !== null) {
                $body["metadata"] = $metadata;
            }

            if ($overwrite !== null) {
                $body["overwrite"] = $overwrite;
            }

            if ($filenameOverride !== null) {
                $body["filenameOverride"] = $filenameOverride;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "post",
                url: "/service/platform/assets/v1.0/upload/url",
                query: $query_params,
                body: $body,
                contentType: "application/json"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * S3 Signed URL upload
         *
         * For the given asset details, a S3 signed URL will be generated, which can be then used to upload your asset.
         * @param string $name name of the file
         * @param string $path Path of the file
         * @param string $format Format of the file
         * @param AccessEnum $access Access level of asset, can be either `public-read` or `private`
         * @param array $tags Tags associated with the file.
         * @param object $metadata Metadata associated with the file.
         * @param bool $overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
         * @param bool $filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
         */
        public function createSignedUrl(
            string|null $name = null,
            string|null $path = null,
            string|null $format = null,
            AccessEnum|null $access = null,
            array|null $tags = null,
            object|null $metadata = null,
            bool|null $overwrite = null,
            bool|null $filenameOverride = null
        ): array {
            $payload = [];

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($name !== null) {
                $body["name"] = $name;
            }

            if ($path !== null) {
                $body["path"] = $path;
            }

            if ($format !== null) {
                $body["format"] = $format;
            }

            if ($access !== null) {
                $body["access"] = $access;
            }

            if ($tags !== null) {
                $body["tags"] = $tags;
            }

            if ($metadata !== null) {
                $body["metadata"] = $metadata;
            }

            if ($overwrite !== null) {
                $body["overwrite"] = $overwrite;
            }

            if ($filenameOverride !== null) {
                $body["filenameOverride"] = $filenameOverride;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "post",
                url: "/service/platform/assets/v1.0/upload/signed-url",
                query: $query_params,
                body: $body,
                contentType: "application/json"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
        /**
         * Signed multipart upload
         *
         * For the given asset details, a presigned URL will be generated, which can be then used to upload your asset in chunks via multipart upload.
         * @param string $name name of the file
         * @param string $path Path of containing folder.
         * @param string $format Format of the file
         * @param AccessEnum $access Access level of asset, can be either `public-read` or `private`
         * @param array $tags Tags associated with the file.
         * @param object $metadata Metadata associated with the file.
         * @param bool $overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
         * @param bool $filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
         * @param int $expiry Expiry time in seconds for the signed URL. Defaults to 3000 seconds.
         */
        public function createSignedUrlV2(
            string|null $name = null,
            string|null $path = null,
            string|null $format = null,
            AccessEnum|null $access = null,
            array|null $tags = null,
            object|null $metadata = null,
            bool|null $overwrite = null,
            bool|null $filenameOverride = null,
            int|null $expiry = null
        ): array {
            $payload = [];

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            if ($name !== null) {
                $body["name"] = $name;
            }

            if ($path !== null) {
                $body["path"] = $path;
            }

            if ($format !== null) {
                $body["format"] = $format;
            }

            if ($access !== null) {
                $body["access"] = $access;
            }

            if ($tags !== null) {
                $body["tags"] = $tags;
            }

            if ($metadata !== null) {
                $body["metadata"] = $metadata;
            }

            if ($overwrite !== null) {
                $body["overwrite"] = $overwrite;
            }

            if ($filenameOverride !== null) {
                $body["filenameOverride"] = $filenameOverride;
            }

            if ($expiry !== null) {
                $body["expiry"] = $expiry;
            }

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "post",
                url: "/service/platform/assets/v2.0/upload/signed-url",
                query: $query_params,
                body: $body,
                contentType: "application/json"
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
    }class Organization
    {
        private PixelbinConfig $config;

        public function __construct(PixelbinConfig $config)
        {
            $this->config = $config;
        }

        /**
         * Get App Details
         *
         * Get App and org details
         */
        public function getAppOrgDetails(): array
        {
            $payload = [];

            // Parameter validation
            json_decode(json_encode($payload), true);

            $body = [];

            // Body validation
            json_decode(json_encode($body), true);

            $query_params = [];

            $response = APIClient::execute(
                conf: $this->config,
                method: "get",
                url: "/service/platform/organization/v1.0/apps/info",
                query: $query_params,
                body: $body,
                contentType: ""
            );
            if ($response["status_code"] !== 200) {
                throw new Exceptions\PDKServerResponseError($response["error_message"], $response["status_code"]);
            }
            return $response["content"];
        }
    }
}
