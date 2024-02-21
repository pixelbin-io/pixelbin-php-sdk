<?php

namespace Pixelbin\Platform {
    use Pixelbin\Platform\Enums\AccessEnum;
    use Pixelbin\Common\Exceptions;

    /**
    * PixelbinClient is a wrapper class for hitting pixelbin apis
    */
    class PixelbinClient {
        public PixelbinConfig $config;
        public Assets $assets;
        public Organization $organization;

        public function __construct(PixelbinConfig $config) {
            $this->config = $config;
            $this->assets = new Assets($config);
            $this->organization = new Organization($config);
        }
    }

    class Assets {
    private PixelbinConfig $config;

    public function __construct(PixelbinConfig $config) {
        $this->config = $config;
    }

    /**
    * Add credentials for a transformation module.
    *
    * Add a transformation modules's credentials for an organization. 
    * @param object credentials Credentials of the plugin
    * @param string pluginId Unique identifier for the plugin this credential belongs to
    */
    public function addCredentialsAsync(
        object $credentials=null, 
        string $pluginId=null
    ): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($credentials !== null)
            $body["credentials"] = $credentials;
        
        if ($pluginId !== null)
            $body["pluginId"] = $pluginId;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"post",
            url:"/service/platform/assets/v1.0/credentials",
            query:$query_params,
            body:$body,
            contentType:"application/json"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Add credentials for a transformation module.
    *
    * Add a transformation modules's credentials for an organization. 
    * @param object credentials Credentials of the plugin
    * @param string pluginId Unique identifier for the plugin this credential belongs to
    */
    public function addCredentials(
        object $credentials=null, 
        string $pluginId=null
    ) {
        return $this->addCredentialsAsync(
            credentials:$credentials, 
            pluginId:$pluginId
        );
    }

    /**
    * Update credentials of a transformation module.
    *
    * Update credentials of a transformation module, for an organization. 
    * @param string $pluginId ID of the plugin whose credentials are being updated
    * @param object credentials Credentials of the plugin
    */
    public function updateCredentialsAsync(
        string $pluginId=null,
        object $credentials=null
    ): array {
        $payload = [];
        
        if ($pluginId !== null)
            $payload["pluginId"] = $pluginId;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($credentials !== null)
            $body["credentials"] = $credentials;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"patch",
            url:"/service/platform/assets/v1.0/credentials/$pluginId",
            query:$query_params,
            body:$body,
            contentType:"application/json"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Update credentials of a transformation module.
    *
    * Update credentials of a transformation module, for an organization. 
    * @param string $pluginId ID of the plugin whose credentials are being updated
    * @param object credentials Credentials of the plugin
    */
    public function updateCredentials(
        string $pluginId=null,
        object $credentials=null
    ) {
        return $this->updateCredentialsAsync(
            pluginId:$pluginId,
            credentials:$credentials
        );
    }

    /**
    * Delete credentials of a transformation module.
    *
    * Delete credentials of a transformation module, for an organization. 
    * @param string $pluginId ID of the plugin whose credentials are being deleted    */
    public function deleteCredentialsAsync(
        string $pluginId=null
    ): array {
        $payload = [];
        
        if ($pluginId !== null)
            $payload["pluginId"] = $pluginId;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"delete",
            url:"/service/platform/assets/v1.0/credentials/$pluginId",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Delete credentials of a transformation module.
    *
    * Delete credentials of a transformation module, for an organization. 
    * @param string $pluginId ID of the plugin whose credentials are being deleted    */
    public function deleteCredentials(
        string $pluginId=null
    ) {
        return $this->deleteCredentialsAsync(
            pluginId:$pluginId
        );
    }

    /**
    * Get file details with _id
    *
    * 
    * @param string $_id _id of File    */
    public function getFileByIdAsync(
        string $_id=null
    ): array {
        $payload = [];
        
        if ($_id !== null)
            $payload["_id"] = $_id;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"get",
            url:"/service/platform/assets/v1.0/files/id/$_id",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Get file details with _id
    *
    * 
    * @param string $_id _id of File    */
    public function getFileById(
        string $_id=null
    ) {
        return $this->getFileByIdAsync(
            _id:$_id
        );
    }

    /**
    * Get file details with fileId
    *
    * 
    * @param string $fileId Combination of `path` and `name` of file    */
    public function getFileByFileIdAsync(
        string $fileId=null
    ): array {
        $payload = [];
        
        if ($fileId !== null)
            $payload["fileId"] = $fileId;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"get",
            url:"/service/platform/assets/v1.0/files/$fileId",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Get file details with fileId
    *
    * 
    * @param string $fileId Combination of `path` and `name` of file    */
    public function getFileByFileId(
        string $fileId=null
    ) {
        return $this->getFileByFileIdAsync(
            fileId:$fileId
        );
    }

    /**
    * Update file details
    *
    * 
    * @param string $fileId Combination of `path` and `name`
    * @param string name Name of the file
    * @param string path Path of the file
    * @param AccessEnum access Access level of asset, can be either `public-read` or `private`
    * @param bool isActive Whether the file is active
    * @param array tags Tags associated with the file
    * @param object metadata Metadata associated with the file
    */
    public function updateFileAsync(
        string $fileId=null,
        string $name=null, 
        string $path=null, 
        AccessEnum $access=null, 
        bool $isActive=null, 
        array $tags=null, 
        object $metadata=null
    ): array {
        $payload = [];
        
        if ($fileId !== null)
            $payload["fileId"] = $fileId;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($name !== null)
            $body["name"] = $name;
        
        if ($path !== null)
            $body["path"] = $path;
        
        if ($access !== null)
            $body["access"] = $access;
        
        if ($isActive !== null)
            $body["isActive"] = $isActive;
        
        if ($tags !== null)
            $body["tags"] = $tags;
        
        if ($metadata !== null)
            $body["metadata"] = $metadata;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"patch",
            url:"/service/platform/assets/v1.0/files/$fileId",
            query:$query_params,
            body:$body,
            contentType:"application/json"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Update file details
    *
    * 
    * @param string $fileId Combination of `path` and `name`
    * @param string name Name of the file
    * @param string path Path of the file
    * @param AccessEnum access Access level of asset, can be either `public-read` or `private`
    * @param bool isActive Whether the file is active
    * @param array tags Tags associated with the file
    * @param object metadata Metadata associated with the file
    */
    public function updateFile(
        string $fileId=null,
        string $name=null, 
        string $path=null, 
        AccessEnum $access=null, 
        bool $isActive=null, 
        array $tags=null, 
        object $metadata=null
    ) {
        return $this->updateFileAsync(
            fileId:$fileId,
            name:$name, 
            path:$path, 
            access:$access, 
            isActive:$isActive, 
            tags:$tags, 
            metadata:$metadata
        );
    }

    /**
    * Delete file
    *
    * 
    * @param string $fileId Combination of `path` and `name`    */
    public function deleteFileAsync(
        string $fileId=null
    ): array {
        $payload = [];
        
        if ($fileId !== null)
            $payload["fileId"] = $fileId;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"delete",
            url:"/service/platform/assets/v1.0/files/$fileId",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Delete file
    *
    * 
    * @param string $fileId Combination of `path` and `name`    */
    public function deleteFile(
        string $fileId=null
    ) {
        return $this->deleteFileAsync(
            fileId:$fileId
        );
    }

    /**
    * Delete multiple files
    *
    * 
    * @param array ids Array of file _ids to delete
    */
    public function deleteFilesAsync(
        array $ids=null
    ): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($ids !== null)
            $body["ids"] = $ids;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"post",
            url:"/service/platform/assets/v1.0/files/delete",
            query:$query_params,
            body:$body,
            contentType:"application/json"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Delete multiple files
    *
    * 
    * @param array ids Array of file _ids to delete
    */
    public function deleteFiles(
        array $ids=null
    ) {
        return $this->deleteFilesAsync(
            ids:$ids
        );
    }

    /**
    * Create folder
    *
    * Create a new folder at the specified path. Also creates the ancestors if they do not exist. 
    * @param string name Name of the folder
    * @param string path Path of the folder
    */
    public function createFolderAsync(
        string $name=null, 
        string $path=null
    ): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($name !== null)
            $body["name"] = $name;
        
        if ($path !== null)
            $body["path"] = $path;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"post",
            url:"/service/platform/assets/v1.0/folders",
            query:$query_params,
            body:$body,
            contentType:"application/json"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Create folder
    *
    * Create a new folder at the specified path. Also creates the ancestors if they do not exist. 
    * @param string name Name of the folder
    * @param string path Path of the folder
    */
    public function createFolder(
        string $name=null, 
        string $path=null
    ) {
        return $this->createFolderAsync(
            name:$name, 
            path:$path
        );
    }

    /**
    * Get folder details
    *
    * Get folder details 
    * @param string|null $path Optional. Folder path
    * @param string|null $name Optional. Folder name    */
    public function getFolderDetailsAsync(
        string $path=null, 
        string $name=null
    ): array {
        $payload = [];
        
        if ($path !== null)
            $payload["path"] = $path;
        
        if ($name !== null)
            $payload["name"] = $name;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        
        if ($path !== null)
            $query_params["path"] = $path;
        
        if ($name !== null)
            $query_params["name"] = $name;
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"get",
            url:"/service/platform/assets/v1.0/folders",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Get folder details
    *
    * Get folder details 
    * @param string|null $path Optional. Folder path
    * @param string|null $name Optional. Folder name    */
    public function getFolderDetails(
        string $path=null, 
        string $name=null
    ) {
        return $this->getFolderDetailsAsync(
            path:$path, 
            name:$name
        );
    }

    /**
    * Update folder details
    *
    * Update folder details. Eg: Soft delete it by making `isActive` as `false`. We currently do not support updating folder name or path. 
    * @param string $folderId combination of `path` and `name`
    * @param bool isActive whether the folder is active
    */
    public function updateFolderAsync(
        string $folderId=null,
        bool $isActive=null
    ): array {
        $payload = [];
        
        if ($folderId !== null)
            $payload["folderId"] = $folderId;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($isActive !== null)
            $body["isActive"] = $isActive;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"patch",
            url:"/service/platform/assets/v1.0/folders/$folderId",
            query:$query_params,
            body:$body,
            contentType:"application/json"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Update folder details
    *
    * Update folder details. Eg: Soft delete it by making `isActive` as `false`. We currently do not support updating folder name or path. 
    * @param string $folderId combination of `path` and `name`
    * @param bool isActive whether the folder is active
    */
    public function updateFolder(
        string $folderId=null,
        bool $isActive=null
    ) {
        return $this->updateFolderAsync(
            folderId:$folderId,
            isActive:$isActive
        );
    }

    /**
    * Delete folder
    *
    * Delete folder and all its children permanently. 
    * @param string $_id _id of folder to be deleted    */
    public function deleteFolderAsync(
        string $_id=null
    ): array {
        $payload = [];
        
        if ($_id !== null)
            $payload["_id"] = $_id;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"delete",
            url:"/service/platform/assets/v1.0/folders/$_id",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Delete folder
    *
    * Delete folder and all its children permanently. 
    * @param string $_id _id of folder to be deleted    */
    public function deleteFolder(
        string $_id=null
    ) {
        return $this->deleteFolderAsync(
            _id:$_id
        );
    }

    /**
    * Get all ancestors of a folder
    *
    * Get all ancestors of a folder, using the folder ID. 
    * @param string $_id _id of the folder    */
    public function getFolderAncestorsAsync(
        string $_id=null
    ): array {
        $payload = [];
        
        if ($_id !== null)
            $payload["_id"] = $_id;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"get",
            url:"/service/platform/assets/v1.0/folders/$_id/ancestors",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Get all ancestors of a folder
    *
    * Get all ancestors of a folder, using the folder ID. 
    * @param string $_id _id of the folder    */
    public function getFolderAncestors(
        string $_id=null
    ) {
        return $this->getFolderAncestorsAsync(
            _id:$_id
        );
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
    public function listFilesAsync(
        string $name=null, 
        string $path=null, 
        string $format=null, 
        array $tags=null, 
        bool $onlyFiles=null, 
        bool $onlyFolders=null, 
        int $pageNo=null, 
        int $pageSize=null, 
        string $sort=null
    ): array {
        $payload = [];
        
        if ($name !== null)
            $payload["name"] = $name;
        
        if ($path !== null)
            $payload["path"] = $path;
        
        if ($format !== null)
            $payload["format"] = $format;
        
        if ($tags !== null)
            $payload["tags"] = $tags;
        
        if ($onlyFiles !== null)
            $payload["onlyFiles"] = $onlyFiles;
        
        if ($onlyFolders !== null)
            $payload["onlyFolders"] = $onlyFolders;
        
        if ($pageNo !== null)
            $payload["pageNo"] = $pageNo;
        
        if ($pageSize !== null)
            $payload["pageSize"] = $pageSize;
        
        if ($sort !== null)
            $payload["sort"] = $sort;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        
        if ($name !== null)
            $query_params["name"] = $name;
        
        if ($path !== null)
            $query_params["path"] = $path;
        
        if ($format !== null)
            $query_params["format"] = $format;
        
        if ($tags !== null)
            $query_params["tags"] = $tags;
        
        if ($onlyFiles !== null)
            $query_params["onlyFiles"] = $onlyFiles;
        
        if ($onlyFolders !== null)
            $query_params["onlyFolders"] = $onlyFolders;
        
        if ($pageNo !== null)
            $query_params["pageNo"] = $pageNo;
        
        if ($pageSize !== null)
            $query_params["pageSize"] = $pageSize;
        
        if ($sort !== null)
            $query_params["sort"] = $sort;
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"get",
            url:"/service/platform/assets/v1.0/listFiles",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
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
        string $name=null, 
        string $path=null, 
        string $format=null, 
        array $tags=null, 
        bool $onlyFiles=null, 
        bool $onlyFolders=null, 
        int $pageNo=null, 
        int $pageSize=null, 
        string $sort=null
    ) {
        return $this->listFilesAsync(
            name:$name, 
            path:$path, 
            format:$format, 
            tags:$tags, 
            onlyFiles:$onlyFiles, 
            onlyFolders:$onlyFolders, 
            pageNo:$pageNo, 
            pageSize:$pageSize, 
            sort:$sort
        );
    }

    /**
    * Get default asset for playground
    *
    * Get default asset for playground    */
    public function getDefaultAssetForPlaygroundAsync(): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"get",
            url:"/service/platform/assets/v1.0/playground/default",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Get default asset for playground
    *
    * Get default asset for playground    */
    public function getDefaultAssetForPlayground() {
        return $this->getDefaultAssetForPlaygroundAsync();
    }

    /**
    * Get all transformation modules
    *
    * Get all transformation modules.     */
    public function getModulesAsync(): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"get",
            url:"/service/platform/assets/v1.0/playground/plugins",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Get all transformation modules
    *
    * Get all transformation modules.     */
    public function getModules() {
        return $this->getModulesAsync();
    }

    /**
    * Get Transformation Module by module identifier
    *
    * Get Transformation Module by module identifier 
    * @param string $identifier identifier of Transformation Module    */
    public function getModuleAsync(
        string $identifier=null
    ): array {
        $payload = [];
        
        if ($identifier !== null)
            $payload["identifier"] = $identifier;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"get",
            url:"/service/platform/assets/v1.0/playground/plugins/$identifier",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Get Transformation Module by module identifier
    *
    * Get Transformation Module by module identifier 
    * @param string $identifier identifier of Transformation Module    */
    public function getModule(
        string $identifier=null
    ) {
        return $this->getModuleAsync(
            identifier:$identifier
        );
    }

    /**
    * Add a preset.
    *
    * Add a preset for an organization. 
    * @param string presetName Name of the preset
    * @param string transformation A chain of transformations, separated by `~`
    * @param object params Parameters object for transformation variables
    */
    public function addPresetAsync(
        string $presetName=null, 
        string $transformation=null, 
        object $params=null
    ): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($presetName !== null)
            $body["presetName"] = $presetName;
        
        if ($transformation !== null)
            $body["transformation"] = $transformation;
        
        if ($params !== null)
            $body["params"] = $params;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"post",
            url:"/service/platform/assets/v1.0/presets",
            query:$query_params,
            body:$body,
            contentType:"application/json"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Add a preset.
    *
    * Add a preset for an organization. 
    * @param string presetName Name of the preset
    * @param string transformation A chain of transformations, separated by `~`
    * @param object params Parameters object for transformation variables
    */
    public function addPreset(
        string $presetName=null, 
        string $transformation=null, 
        object $params=null
    ) {
        return $this->addPresetAsync(
            presetName:$presetName, 
            transformation:$transformation, 
            params:$params
        );
    }

    /**
    * Get all presets.
    *
    * Get all presets of an organization.     */
    public function getPresetsAsync(): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"get",
            url:"/service/platform/assets/v1.0/presets",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Get all presets.
    *
    * Get all presets of an organization.     */
    public function getPresets() {
        return $this->getPresetsAsync();
    }

    /**
    * Update a preset.
    *
    * Update a preset of an organization. 
    * @param string $presetName Name of the preset to be updated
    * @param bool archived Indicates if the preset has been archived
    */
    public function updatePresetAsync(
        string $presetName=null,
        bool $archived=null
    ): array {
        $payload = [];
        
        if ($presetName !== null)
            $payload["presetName"] = $presetName;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($archived !== null)
            $body["archived"] = $archived;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"patch",
            url:"/service/platform/assets/v1.0/presets/$presetName",
            query:$query_params,
            body:$body,
            contentType:"application/json"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Update a preset.
    *
    * Update a preset of an organization. 
    * @param string $presetName Name of the preset to be updated
    * @param bool archived Indicates if the preset has been archived
    */
    public function updatePreset(
        string $presetName=null,
        bool $archived=null
    ) {
        return $this->updatePresetAsync(
            presetName:$presetName,
            archived:$archived
        );
    }

    /**
    * Delete a preset.
    *
    * Delete a preset of an organization. 
    * @param string $presetName Name of the preset to be deleted    */
    public function deletePresetAsync(
        string $presetName=null
    ): array {
        $payload = [];
        
        if ($presetName !== null)
            $payload["presetName"] = $presetName;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"delete",
            url:"/service/platform/assets/v1.0/presets/$presetName",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Delete a preset.
    *
    * Delete a preset of an organization. 
    * @param string $presetName Name of the preset to be deleted    */
    public function deletePreset(
        string $presetName=null
    ) {
        return $this->deletePresetAsync(
            presetName:$presetName
        );
    }

    /**
    * Get a preset.
    *
    * Get a preset of an organization. 
    * @param string $presetName Name of the preset to be fetched    */
    public function getPresetAsync(
        string $presetName=null
    ): array {
        $payload = [];
        
        if ($presetName !== null)
            $payload["presetName"] = $presetName;
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"get",
            url:"/service/platform/assets/v1.0/presets/$presetName",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Get a preset.
    *
    * Get a preset of an organization. 
    * @param string $presetName Name of the preset to be fetched    */
    public function getPreset(
        string $presetName=null
    ) {
        return $this->getPresetAsync(
            presetName:$presetName
        );
    }

    /**
    * Upload File
    *
    * Upload File to Pixelbin
    * @param resource file Asset file
    * @param string path Path where you want to store the asset
    * @param string name Name of the asset, if not provided name of the file will be used. Note - The provided name will be slugified to make it URL safe
    * @param AccessEnum access Access level of asset, can be either `public-read` or `private`
    * @param array tags Asset tags
    * @param object metadata Asset related metadata
    * @param bool overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
    * @param bool filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
    */
    public function fileUploadAsync(
        mixed $file=null, 
        string $path=null, 
        string $name=null, 
        AccessEnum $access=null, 
        array $tags=null, 
        object $metadata=null, 
        bool $overwrite=null, 
        bool $filenameOverride=null
    ): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($file !== null)
            $body["file"] = $file;
        
        if ($path !== null)
            $body["path"] = $path;
        
        if ($name !== null)
            $body["name"] = $name;
        
        if ($access !== null)
            $body["access"] = $access;
        
        if ($tags !== null)
            $body["tags"] = $tags;
        
        if ($metadata !== null)
            $body["metadata"] = $metadata;
        
        if ($overwrite !== null)
            $body["overwrite"] = $overwrite;
        
        if ($filenameOverride !== null)
            $body["filenameOverride"] = $filenameOverride;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"post",
            url:"/service/platform/assets/v1.0/upload/direct",
            query:$query_params,
            body:$body,
            contentType:"multipart/form-data"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Upload File
    *
    * Upload File to Pixelbin
    * @param resource file Asset file
    * @param string path Path where you want to store the asset
    * @param string name Name of the asset, if not provided name of the file will be used. Note - The provided name will be slugified to make it URL safe
    * @param AccessEnum access Access level of asset, can be either `public-read` or `private`
    * @param array tags Asset tags
    * @param object metadata Asset related metadata
    * @param bool overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
    * @param bool filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
    */
    public function fileUpload(
        mixed $file=null, 
        string $path=null, 
        string $name=null, 
        AccessEnum $access=null, 
        array $tags=null, 
        object $metadata=null, 
        bool $overwrite=null, 
        bool $filenameOverride=null
    ) {
        return $this->fileUploadAsync(
            file:$file, 
            path:$path, 
            name:$name, 
            access:$access, 
            tags:$tags, 
            metadata:$metadata, 
            overwrite:$overwrite, 
            filenameOverride:$filenameOverride
        );
    }

    /**
    * Upload Asset with url
    *
    * Upload Asset with url
    * @param string url Asset URL
    * @param string path Path where you want to store the asset
    * @param string name Name of the asset, if not provided name of the file will be used. Note - The provided name will be slugified to make it URL safe
    * @param AccessEnum access Access level of asset, can be either `public-read` or `private`
    * @param array tags Asset tags
    * @param object metadata Asset related metadata
    * @param bool overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
    * @param bool filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
    */
    public function urlUploadAsync(
        string $url=null, 
        string $path=null, 
        string $name=null, 
        AccessEnum $access=null, 
        array $tags=null, 
        object $metadata=null, 
        bool $overwrite=null, 
        bool $filenameOverride=null
    ): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($url !== null)
            $body["url"] = $url;
        
        if ($path !== null)
            $body["path"] = $path;
        
        if ($name !== null)
            $body["name"] = $name;
        
        if ($access !== null)
            $body["access"] = $access;
        
        if ($tags !== null)
            $body["tags"] = $tags;
        
        if ($metadata !== null)
            $body["metadata"] = $metadata;
        
        if ($overwrite !== null)
            $body["overwrite"] = $overwrite;
        
        if ($filenameOverride !== null)
            $body["filenameOverride"] = $filenameOverride;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"post",
            url:"/service/platform/assets/v1.0/upload/url",
            query:$query_params,
            body:$body,
            contentType:"application/json"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Upload Asset with url
    *
    * Upload Asset with url
    * @param string url Asset URL
    * @param string path Path where you want to store the asset
    * @param string name Name of the asset, if not provided name of the file will be used. Note - The provided name will be slugified to make it URL safe
    * @param AccessEnum access Access level of asset, can be either `public-read` or `private`
    * @param array tags Asset tags
    * @param object metadata Asset related metadata
    * @param bool overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
    * @param bool filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
    */
    public function urlUpload(
        string $url=null, 
        string $path=null, 
        string $name=null, 
        AccessEnum $access=null, 
        array $tags=null, 
        object $metadata=null, 
        bool $overwrite=null, 
        bool $filenameOverride=null
    ) {
        return $this->urlUploadAsync(
            url:$url, 
            path:$path, 
            name:$name, 
            access:$access, 
            tags:$tags, 
            metadata:$metadata, 
            overwrite:$overwrite, 
            filenameOverride:$filenameOverride
        );
    }

    /**
    * S3 Signed URL upload
    *
    * For the given asset details, a S3 signed URL will be generated, which can be then used to upload your asset. 
    * @param string name name of the file
    * @param string path Path of the file
    * @param string format Format of the file
    * @param AccessEnum access Access level of asset, can be either `public-read` or `private`
    * @param array tags Tags associated with the file.
    * @param object metadata Metadata associated with the file.
    * @param bool overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
    * @param bool filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
    */
    public function createSignedUrlAsync(
        string $name=null, 
        string $path=null, 
        string $format=null, 
        AccessEnum $access=null, 
        array $tags=null, 
        object $metadata=null, 
        bool $overwrite=null, 
        bool $filenameOverride=null
    ): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($name !== null)
            $body["name"] = $name;
        
        if ($path !== null)
            $body["path"] = $path;
        
        if ($format !== null)
            $body["format"] = $format;
        
        if ($access !== null)
            $body["access"] = $access;
        
        if ($tags !== null)
            $body["tags"] = $tags;
        
        if ($metadata !== null)
            $body["metadata"] = $metadata;
        
        if ($overwrite !== null)
            $body["overwrite"] = $overwrite;
        
        if ($filenameOverride !== null)
            $body["filenameOverride"] = $filenameOverride;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"post",
            url:"/service/platform/assets/v1.0/upload/signed-url",
            query:$query_params,
            body:$body,
            contentType:"application/json"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * S3 Signed URL upload
    *
    * For the given asset details, a S3 signed URL will be generated, which can be then used to upload your asset. 
    * @param string name name of the file
    * @param string path Path of the file
    * @param string format Format of the file
    * @param AccessEnum access Access level of asset, can be either `public-read` or `private`
    * @param array tags Tags associated with the file.
    * @param object metadata Metadata associated with the file.
    * @param bool overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
    * @param bool filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
    */
    public function createSignedUrl(
        string $name=null, 
        string $path=null, 
        string $format=null, 
        AccessEnum $access=null, 
        array $tags=null, 
        object $metadata=null, 
        bool $overwrite=null, 
        bool $filenameOverride=null
    ) {
        return $this->createSignedUrlAsync(
            name:$name, 
            path:$path, 
            format:$format, 
            access:$access, 
            tags:$tags, 
            metadata:$metadata, 
            overwrite:$overwrite, 
            filenameOverride:$filenameOverride
        );
    }

    /**
    * Signed multipart upload
    *
    * For the given asset details, a presigned URL will be generated, which can be then used to upload your asset in chunks via multipart upload.
    * @param string name name of the file
    * @param string path Path of containing folder.
    * @param string format Format of the file
    * @param AccessEnum access Access level of asset, can be either `public-read` or `private`
    * @param array tags Tags associated with the file.
    * @param object metadata Metadata associated with the file.
    * @param bool overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
    * @param bool filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
    * @param int expiry Expiry time in seconds for the signed URL. Defaults to 3000 seconds.
    */
    public function createSignedUrlV2Async(
        string $name=null, 
        string $path=null, 
        string $format=null, 
        AccessEnum $access=null, 
        array $tags=null, 
        object $metadata=null, 
        bool $overwrite=null, 
        bool $filenameOverride=null, 
        int $expiry=null
    ): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        if ($name !== null)
            $body["name"] = $name;
        
        if ($path !== null)
            $body["path"] = $path;
        
        if ($format !== null)
            $body["format"] = $format;
        
        if ($access !== null)
            $body["access"] = $access;
        
        if ($tags !== null)
            $body["tags"] = $tags;
        
        if ($metadata !== null)
            $body["metadata"] = $metadata;
        
        if ($overwrite !== null)
            $body["overwrite"] = $overwrite;
        
        if ($filenameOverride !== null)
            $body["filenameOverride"] = $filenameOverride;
        
        if ($expiry !== null)
            $body["expiry"] = $expiry;
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"post",
            url:"/service/platform/assets/v2.0/upload/signed-url",
            query:$query_params,
            body:$body,
            contentType:"application/json"
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Signed multipart upload
    *
    * For the given asset details, a presigned URL will be generated, which can be then used to upload your asset in chunks via multipart upload.
    * @param string name name of the file
    * @param string path Path of containing folder.
    * @param string format Format of the file
    * @param AccessEnum access Access level of asset, can be either `public-read` or `private`
    * @param array tags Tags associated with the file.
    * @param object metadata Metadata associated with the file.
    * @param bool overwrite Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`.
    * @param bool filenameOverride If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised.
    * @param int expiry Expiry time in seconds for the signed URL. Defaults to 3000 seconds.
    */
    public function createSignedUrlV2(
        string $name=null, 
        string $path=null, 
        string $format=null, 
        AccessEnum $access=null, 
        array $tags=null, 
        object $metadata=null, 
        bool $overwrite=null, 
        bool $filenameOverride=null, 
        int $expiry=null
    ) {
        return $this->createSignedUrlV2Async(
            name:$name, 
            path:$path, 
            format:$format, 
            access:$access, 
            tags:$tags, 
            metadata:$metadata, 
            overwrite:$overwrite, 
            filenameOverride:$filenameOverride, 
            expiry:$expiry
        );
    }


}
class Organization {
    private PixelbinConfig $config;

    public function __construct(PixelbinConfig $config) {
        $this->config = $config;
    }

    /**
    * Get App Details
    *
    * Get App and org details    */
    public function getAppOrgDetailsAsync(): array {
        $payload = [];
        

        // Parameter validation
        json_decode(json_encode($payload), true);

        $body = [];
        
        // Body validation
        json_decode(json_encode($body), true);

        $query_params = [];
        

        $response = APIClient::execute(
            conf:$this->config,
            method:"get",
            url:"/service/platform/organization/v1.0/apps/info",
            query:$query_params,
            body:$body,
            contentType:""
        );
        if ($response["status_code"] !== 200)
            throw new Exceptions\PixelbinServerResponseError($response["error_message"]);
        return $response["content"];
    }
    /**
    * Get App Details
    *
    * Get App and org details    */
    public function getAppOrgDetails() {
        return $this->getAppOrgDetailsAsync();
    }


}

}
