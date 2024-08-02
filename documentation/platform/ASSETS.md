# Assets Methods

[Back to Pixelbin API docs](./README.md)

Asset Uploader Service

* [addCredentials](#addcredentials)
* [updateCredentials](#updatecredentials)
* [deleteCredentials](#deletecredentials)
* [getFileById](#getfilebyid)
* [getFileByFileId](#getfilebyfileid)
* [updateFile](#updatefile)
* [deleteFile](#deletefile)
* [deleteFiles](#deletefiles)
* [createFolder](#createfolder)
* [getFolderDetails](#getfolderdetails)
* [updateFolder](#updatefolder)
* [deleteFolder](#deletefolder)
* [getFolderAncestors](#getfolderancestors)
* [listFiles](#listfiles)
* [getDefaultAssetForPlayground](#getdefaultassetforplayground)
* [getModules](#getmodules)
* [getModule](#getmodule)
* [addPreset](#addpreset)
* [getPresets](#getpresets)
* [updatePreset](#updatepreset)
* [deletePreset](#deletepreset)
* [getPreset](#getpreset)
* [fileUpload](#fileupload)
* [urlUpload](#urlupload)
* [createSignedUrl](#createsignedurl)
* [createSignedUrlV2](#createsignedurlv2)

## Methods with example and description

### addCredentials

**Summary**: Add credentials for a transformation module.

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->addCredentials(
        credentials: {"region":"ap-south-1","accessKeyId":"123456789ABC","secretAccessKey":"DUMMY1234567890"},
        pluginId: "awsRek"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| credentials | object | yes | Credentials of the plugin |
| pluginId | string | yes | Unique identifier for the plugin this credential belongs to |

Add a transformation modules's credentials for an organization.

*Returned Response:*

[AddCredentialsResponse](#addcredentialsresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "123ee789-7ae8-4336-b9bd-e4f33c049002",
  "createdAt": "2022-10-04T09:52:09.545Z",
  "updatedAt": "2022-10-04T09:52:09.545Z",
  "orgId": 23,
  "pluginId": "awsRek"
}
```

</details>

### updateCredentials

**Summary**: Update credentials of a transformation module.

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->updateCredentials(
        pluginId: "awsRek",
        credentials: {"region":"ap-south-1","accessKeyId":"123456789ABC","secretAccessKey":"DUMMY1234567890"}
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| pluginId | string | yes | ID of the plugin whose credentials are being updated |
| credentials | object | yes | Credentials of the plugin |

Update credentials of a transformation module, for an organization.

*Returned Response:*

[AddCredentialsResponse](#addcredentialsresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "123ee789-7ae8-4336-b9bd-e4f33c049002",
  "createdAt": "2022-10-04T09:52:09.545Z",
  "updatedAt": "2022-10-04T09:52:09.545Z",
  "orgId": 23,
  "pluginId": "awsRek"
}
```

</details>

### deleteCredentials

**Summary**: Delete credentials of a transformation module.

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->deleteCredentials(
        pluginId: "awsRek"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| pluginId | string | yes | ID of the plugin whose credentials are being deleted |

Delete credentials of a transformation module, for an organization.

*Returned Response:*

[AddCredentialsResponse](#addcredentialsresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "123ee789-7ae8-4336-b9bd-e4f33c049002",
  "createdAt": "2022-10-04T09:52:09.545Z",
  "updatedAt": "2022-10-04T09:52:09.545Z",
  "orgId": 23,
  "pluginId": "awsRek"
}
```

</details>

### getFileById

**Summary**: Get file details with _id

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->getFileById(
        _id: "c9138153-94ea-4dbe-bea9-65d43dba85ae"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| _id | string | yes | _id of File |

*Returned Response:*

[FilesResponse](#filesresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "dummy-uuid",
  "name": "asset",
  "path": "dir",
  "fileId": "dir/asset",
  "format": "jpeg",
  "size": 1000,
  "access": "private",
  "isActive": true,
  "tags": [
    "tag1",
    "tag2"
  ],
  "metadata": {
    "key": "value"
  },
  "url": "https://domain.com/filename.jpeg"
}
```

</details>

### getFileByFileId

**Summary**: Get file details with fileId

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->getFileByFileId(
        fileId: "path/to/file/name"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| fileId | string | yes | Combination of `path` and `name` of file |

*Returned Response:*

[FilesResponse](#filesresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "dummy-uuid",
  "name": "asset",
  "path": "dir",
  "fileId": "dir/asset",
  "format": "jpeg",
  "size": 1000,
  "access": "private",
  "isActive": true,
  "tags": [
    "tag1",
    "tag2"
  ],
  "metadata": {
    "key": "value"
  },
  "url": "https://domain.com/filename.jpeg"
}
```

</details>

### updateFile

**Summary**: Update file details

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;
use Pixelbin\Platform\Enums\AccessEnum;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->updateFile(
        fileId: "path/to/file/name",
        name: "asset",
        path: "dir",
        access: AccessEnum::PRIVATE,
        isActive: false,
        tags: ["tag1","tag2"],
        metadata: new stdClass()
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| fileId | string | yes | Combination of `path` and `name` |
| name | string | no | Name of the file |
| path | string | no | Path of the file |
| access | AccessEnum | no | Access level of asset, can be either `public-read` or `private` |
| isActive | bool | no | Whether the file is active |
| tags | array | no | Tags associated with the file |
| metadata | object | no | Metadata associated with the file |

*Returned Response:*

[FilesResponse](#filesresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "dummy-uuid",
  "name": "asset",
  "path": "dir",
  "fileId": "dir/asset",
  "format": "jpeg",
  "size": 1000,
  "access": "private",
  "isActive": true,
  "tags": [
    "tag1",
    "tag2"
  ],
  "metadata": {
    "key": "value"
  },
  "url": "https://domain.com/filename.jpeg"
}
```

</details>

### deleteFile

**Summary**: Delete file

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->deleteFile(
        fileId: "path/to/file/name"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| fileId | string | yes | Combination of `path` and `name` |

*Returned Response:*

[FilesResponse](#filesresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "dummy-uuid",
  "name": "asset",
  "path": "dir",
  "fileId": "dir/asset",
  "format": "jpeg",
  "size": 1000,
  "access": "private",
  "isActive": true,
  "tags": [
    "tag1",
    "tag2"
  ],
  "metadata": {
    "key": "value"
  },
  "url": "https://domain.com/filename.jpeg"
}
```

</details>

### deleteFiles

**Summary**: Delete multiple files

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->deleteFiles(
        ids: ["_id_1","_id_2","_id_3"]
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| ids | array | yes | Array of file _ids to delete |

*Returned Response:*

[array](#array)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
[
  {
    "_id": "dummy-uuid",
    "name": "asset",
    "path": "dir",
    "fileId": "dir/asset",
    "format": "jpeg",
    "size": 1000,
    "access": "private",
    "isActive": true,
    "tags": [
      "tag1",
      "tag2"
    ],
    "metadata": {
      "key": "value"
    },
    "url": "https://domain.com/filename.jpeg"
  }
]
```

</details>

### createFolder

**Summary**: Create folder

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->createFolder(
        name: "subDir",
        path: "dir"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| name | string | yes | Name of the folder |
| path | string | no | Path of the folder |

Create a new folder at the specified path. Also creates the ancestors if they do not exist.

*Returned Response:*

[FoldersResponse](#foldersresponse)

Success - List of all created folders

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "dummy-uuid",
  "name": "subDir",
  "path": "dir",
  "isActive": true
}
```

</details>

### getFolderDetails

**Summary**: Get folder details

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->getFolderDetails(
        path: "dir1/dir2",
        name: "dir"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| path | string | no | Folder path |
| name | string | no | Folder name |

Get folder details

*Returned Response:*

[exploreItem](#exploreitem)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
[
  {
    "_id": "dummy-uuid",
    "createdAt": "2022-10-05T10:43:04.117Z",
    "updatedAt": "2022-10-05T10:43:04.117Z",
    "name": "asset2",
    "type": "file",
    "path": "dir",
    "fileId": "dir/asset2",
    "format": "jpeg",
    "size": 1000,
    "access": "private",
    "metadata": {},
    "height": 100,
    "width": 100
  }
]
```

</details>

### updateFolder

**Summary**: Update folder details

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->updateFolder(
        folderId: "path/to/folder/name",
        isActive: false
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| folderId | string | yes | combination of `path` and `name` |
| isActive | bool | no | whether the folder is active |

Update folder details. Eg: Soft delete it by making `isActive` as `false`. We currently do not support updating folder name or path.

*Returned Response:*

[FoldersResponse](#foldersresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "dummy-uuid",
  "name": "subDir",
  "path": "dir",
  "isActive": true
}
```

</details>

### deleteFolder

**Summary**: Delete folder

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->deleteFolder(
        _id: "c9138153-94ea-4dbe-bea9-65d43dba85ae"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| _id | string | yes | _id of folder to be deleted |

Delete folder and all its children permanently.

*Returned Response:*

[FoldersResponse](#foldersresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "dummy-uuid",
  "name": "subDir",
  "path": "dir",
  "isActive": true
}
```

</details>

### getFolderAncestors

**Summary**: Get all ancestors of a folder

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->getFolderAncestors(
        _id: "c9138153-94ea-4dbe-bea9-65d43dba85ae"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| _id | string | yes | _id of the folder |

Get all ancestors of a folder, using the folder ID.

*Returned Response:*

[GetAncestorsResponse](#getancestorsresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "folder": {
    "_id": "dummy-uuid",
    "name": "subDir",
    "path": "dir1/dir2",
    "isActive": true
  },
  "ancestors": [
    {
      "_id": "dummy-uuid-2",
      "name": "dir1",
      "path": "",
      "isActive": true
    },
    {
      "_id": "dummy-uuid-2",
      "name": "dir2",
      "path": "dir1",
      "isActive": true
    }
  ]
}
```

</details>

### listFiles

**Summary**: List and search files and folders.

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->listFiles(
        name: "cat",
        path: "cat-photos",
        format: "jpeg",
        tags: ["cats","animals"],
        onlyFiles: "false",
        onlyFolders: "false",
        pageNo: 1,
        pageSize: 10,
        sort: "name"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| name | string | no | Find items with matching name |
| path | string | no | Find items with matching path |
| format | string | no | Find items with matching format |
| tags | array | no | Find items containing these tags |
| onlyFiles | bool | no | If true will fetch only files |
| onlyFolders | bool | no | If true will fetch only folders |
| pageNo | int | no | Page No. |
| pageSize | int | no | Page Size |
| sort | string | no | Key to sort results by. A "-" suffix will sort results in descending orders.  |

List all files and folders in root folder. Search for files if name is provided. If path is provided, search in the specified path.

*Returned Response:*

[ListFilesResponse](#listfilesresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "items": [
    {
      "_id": "dummy-uuid",
      "name": "dir",
      "type": "folder"
    },
    {
      "_id": "dummy-uuid",
      "name": "asset2",
      "type": "file",
      "path": "dir",
      "fileId": "dir/asset2",
      "format": "jpeg",
      "size": 1000,
      "access": "private"
    },
    {
      "_id": "dummy-uuid",
      "name": "asset1",
      "type": "file",
      "path": "dir",
      "fileId": "dir/asset1",
      "format": "jpeg",
      "size": 1000,
      "access": "private"
    }
  ],
  "page": {
    "type": "number",
    "size": 4,
    "current": 1,
    "hasNext": false
  }
}
```

</details>

### getDefaultAssetForPlayground

**Summary**: Get default asset for playground

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->getDefaultAssetForPlayground(
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

Get default asset for playground

*Returned Response:*

[UploadResponse](#uploadresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "isActive": true,
  "orgId": "1",
  "type": "file",
  "name": "abc.jpeg",
  "path": "/xyz",
  "fileId": "xyz/abc.jpeg",
  "format": "jpeg",
  "size": 100,
  "tags": null,
  "metadata": null,
  "access": "public-read",
  "width": null,
  "height": null,
  "meta": {},
  "context": null,
  "assetType": null,
  "isOriginal": true,
  "_id": "35675e3a-5dd8-4b19-a611-1cb64e676c5e",
  "url": "https://cdn.pixelbin.io/v2/dummy-cloudname/original/xyz/abc.jpeg"
}
```

</details>

### getModules

**Summary**: Get all transformation modules

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->getModules(
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

Get all transformation modules.

*Returned Response:*

[TransformationModulesResponse](#transformationmodulesresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "delimiters": {
    "operationSeparator": "~",
    "parameterSeparator": ":"
  },
  "plugins": {
    "erase": {
      "identifier": "erase",
      "name": "EraseBG",
      "description": "EraseBG Background Removal Module",
      "credentials": {
        "required": false
      },
      "operations": [
        {
          "params": {
            "name": "Industry Type",
            "type": "enum",
            "enum": [
              "general",
              "ecommerce"
            ],
            "default": "general",
            "identifier": "i",
            "title": "Industry type"
          },
          "displayName": "Remove background of an image",
          "method": "bg",
          "description": "Remove the background of any image"
        }
      ],
      "enabled": true
    }
  },
  "presets": [
    {
      "_id": "dummy-id",
      "createdAt": "2022-02-14T10:06:17.803Z",
      "updatedAt": "2022-02-14T10:06:17.803Z",
      "isActive": true,
      "orgId": "265",
      "presetName": "compressor",
      "transformation": "t.compress(q:95)",
      "archived": false
    }
  ]
}
```

</details>

### getModule

**Summary**: Get Transformation Module by module identifier

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->getModule(
        identifier: "t"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| identifier | string | yes | identifier of Transformation Module |

Get Transformation Module by module identifier

*Returned Response:*

[TransformationModuleResponse](#transformationmoduleresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "identifier": "erase",
  "name": "EraseBG",
  "description": "EraseBG Background Removal Module",
  "credentials": {
    "required": false
  },
  "operations": [
    {
      "params": {
        "name": "Industry Type",
        "type": "enum",
        "enum": [
          "general",
          "ecommerce"
        ],
        "default": "general",
        "identifier": "i",
        "title": "Industry type"
      },
      "displayName": "Remove background of an image",
      "method": "bg",
      "description": "Remove the background of any image"
    }
  ],
  "enabled": true
}
```

</details>

### addPreset

**Summary**: Add a preset.

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->addPreset(
        presetName: "pre-set_1",
        transformation: "t.resize(w:$w,h:$h)~t.extract()",
        params: {"w":{"type":"integer","default":200},"h":{"type":"integer","default":400}}
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| presetName | string | yes | Name of the preset |
| transformation | string | yes | A chain of transformations, separated by `~` |
| params | object | no | Parameters object for transformation variables |

Add a preset for an organization.

*Returned Response:*

[AddPresetResponse](#addpresetresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "orgId": 23,
  "presetName": "pre-set_1",
  "transformation": "t.resize(w:$w,h:$h)~t.extract()",
  "params": {
    "w": {
      "type": "integer",
      "default": 200
    },
    "h": {
      "type": "integer",
      "default": 400
    }
  },
  "_id": "821c6816-3cbb-40fd-8629-0098007fc949",
  "createdAt": "2024-03-21T10:35:47.822Z",
  "updatedAt": "2024-03-21T10:35:47.822Z",
  "isActive": true,
  "archived": false
}
```

</details>

### getPresets

**Summary**: Get presets for an organization

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->getPresets(
        pageNo: 1,
        pageSize: 5,
        name: "t_0",
        transformation: "t.resize(a:0)",
        archived: "false",
        sort: ["updatedAt"]
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| pageNo | int | no | Page number |
| pageSize | int | no | Page size |
| name | string | no | Preset name |
| transformation | string | no | Transformation applied |
| archived | bool | no | Indicates whether the preset is archived or not |
| sort | array | no | Sort the results by a specific key |

Retrieve presets for a specific organization.

*Returned Response:*

[GetPresetsResponse](#getpresetsresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "items": [
    {
      "_id": "f1ae2fc0-a931-4cef-bd1a-3644dad5ae9b",
      "createdAt": "2024-03-21T10:45:06.623Z",
      "updatedAt": "2024-03-21T10:45:06.623Z",
      "isActive": true,
      "orgId": 23,
      "presetName": "t_0",
      "transformation": "t.resize(a:0)",
      "archived": false,
      "params": {}
    },
    {
      "_id": "b40a03f1-7fa5-42b1-8cc6-ffe84c9e6629",
      "createdAt": "2024-03-21T10:45:06.637Z",
      "updatedAt": "2024-03-21T10:45:06.637Z",
      "isActive": true,
      "orgId": 23,
      "presetName": "t_1",
      "transformation": "t.resize(a:1)",
      "archived": false,
      "params": {}
    }
  ],
  "page": {
    "type": "number",
    "size": 2,
    "current": 1,
    "hasNext": true,
    "itemTotal": 10
  }
}
```

</details>

### updatePreset

**Summary**: Update a preset.

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->updatePreset(
        presetName: "p1",
        archived: true
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| presetName | string | yes | Name of the preset to be updated |
| archived | bool | yes | Indicates if the preset has been archived |

Update a preset of an organization.

*Returned Response:*

[AddPresetResponse](#addpresetresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "orgId": 23,
  "presetName": "pre-set_1",
  "transformation": "t.resize(w:$w,h:$h)~t.extract()",
  "params": {
    "w": {
      "type": "integer",
      "default": 200
    },
    "h": {
      "type": "integer",
      "default": 400
    }
  },
  "_id": "821c6816-3cbb-40fd-8629-0098007fc949",
  "createdAt": "2024-03-21T10:35:47.822Z",
  "updatedAt": "2024-03-21T10:35:47.822Z",
  "isActive": true,
  "archived": true
}
```

</details>

### deletePreset

**Summary**: Delete a preset.

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->deletePreset(
        presetName: "pre-set_1"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| presetName | string | yes | Name of the preset to be deleted |

Delete a preset of an organization.

*Returned Response:*

[AddPresetResponse](#addpresetresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "orgId": 23,
  "presetName": "pre-set_1",
  "transformation": "t.resize(w:$w,h:$h)~t.extract()",
  "params": {
    "w": {
      "type": "integer",
      "default": 200
    },
    "h": {
      "type": "integer",
      "default": 400
    }
  },
  "_id": "821c6816-3cbb-40fd-8629-0098007fc949",
  "createdAt": "2024-03-21T10:35:47.822Z",
  "updatedAt": "2024-03-21T10:35:47.822Z",
  "isActive": true,
  "archived": false
}
```

</details>

### getPreset

**Summary**: Get a preset.

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->getPreset(
        presetName: "p1"
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| presetName | string | yes | Name of the preset to be fetched |

Get a preset of an organization.

*Returned Response:*

[AddPresetResponse](#addpresetresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "orgId": 23,
  "presetName": "p1",
  "transformation": "t.resize(w:$w,h:$h)~t.extract()",
  "params": {
    "w": {
      "type": "integer",
      "default": 200
    },
    "h": {
      "type": "integer",
      "default": 400
    }
  },
  "_id": "821c6816-3cbb-40fd-8629-0098007fc949",
  "createdAt": "2024-03-21T10:35:47.822Z",
  "updatedAt": "2024-03-21T10:35:47.822Z",
  "isActive": true,
  "archived": false
}
```

</details>

### fileUpload

**Summary**: Upload File

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;
use Pixelbin\Platform\Enums\AccessEnum;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->fileUpload(
        file: fopen("your-file-path", "r"),
        path: "path/to/containing/folder",
        name: "filename",
        access: AccessEnum::PUBLIC_READ,
        tags: ["tag1","tag2"],
        metadata: new stdClass(),
        overwrite: false,
        filenameOverride: true
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| file | mixed | yes | Asset file |
| path | string | no | Path where you want to store the asset |
| name | string | no | Name of the asset, if not provided name of the file will be used. Note - The provided name will be slugified to make it URL safe |
| access | AccessEnum | no | Access level of asset, can be either `public-read` or `private` |
| tags | array | no | Asset tags |
| metadata | object | no | Asset related metadata |
| overwrite | bool | no | Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`. |
| filenameOverride | bool | no | If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised. |

Upload File to Pixelbin

*Returned Response:*

[UploadResponse](#uploadresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "dummy-uuid",
  "name": "asset",
  "path": "dir",
  "fileId": "dir/asset",
  "format": "jpeg",
  "size": 1000,
  "access": "private",
  "isActive": true,
  "tags": [
    "tag1",
    "tag2"
  ],
  "metadata": {
    "key": "value"
  },
  "url": "https://domain.com/filename.jpeg"
}
```

</details>

### urlUpload

**Summary**: Upload Asset with url

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;
use Pixelbin\Platform\Enums\AccessEnum;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->urlUpload(
        url: "www.dummy.com/image.png",
        path: "path/to/containing/folder",
        name: "filename",
        access: AccessEnum::PUBLIC_READ,
        tags: ["tag1","tag2"],
        metadata: new stdClass(),
        overwrite: false,
        filenameOverride: true
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| url | string | yes | Asset URL |
| path | string | no | Path where you want to store the asset |
| name | string | no | Name of the asset, if not provided name of the file will be used. Note - The provided name will be slugified to make it URL safe |
| access | AccessEnum | no | Access level of asset, can be either `public-read` or `private` |
| tags | array | no | Asset tags |
| metadata | object | no | Asset related metadata |
| overwrite | bool | no | Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`. |
| filenameOverride | bool | no | If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised. |

Upload Asset with url

*Returned Response:*

[UploadResponse](#uploadresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "_id": "dummy-uuid",
  "name": "asset",
  "path": "dir",
  "fileId": "dir/asset",
  "format": "jpeg",
  "size": 1000,
  "access": "private",
  "isActive": true,
  "tags": [
    "tag1",
    "tag2"
  ],
  "metadata": {
    "key": "value"
  },
  "url": "https://domain.com/filename.jpeg"
}
```

</details>

### createSignedUrl

**Summary**: S3 Signed URL upload

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;
use Pixelbin\Platform\Enums\AccessEnum;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->createSignedUrl(
        name: "filename",
        path: "path/to/containing/folder",
        format: "jpeg",
        access: AccessEnum::PUBLIC_READ,
        tags: ["tag1","tag2"],
        metadata: new stdClass(),
        overwrite: false,
        filenameOverride: true
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| name | string | no | name of the file |
| path | string | no | Path of the file |
| format | string | no | Format of the file |
| access | AccessEnum | no | Access level of asset, can be either `public-read` or `private` |
| tags | array | no | Tags associated with the file. |
| metadata | object | no | Metadata associated with the file. |
| overwrite | bool | no | Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`. |
| filenameOverride | bool | no | If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised. |

For the given asset details, a S3 signed URL will be generated, which can be then used to upload your asset.

*Returned Response:*

[SignedUploadResponse](#signeduploadresponse)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "s3PresignedUrl": {
    "url": "https://domain.com/xyz",
    "fields": {
      "field1": "value",
      "field2": "value"
    }
  }
}
```

</details>

### createSignedUrlV2

**Summary**: Signed multipart upload

```php
<?php

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;
use Pixelbin\Platform\Enums\AccessEnum;

$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_SECRET_TOKEN",
    "integrationPlatform" => "Your App Name/1.2.3 (Platform Name/3.2.1)" // this is optional
])

$pixelbin = new PixelbinClient(config: $config)

// Method call
try {
    $result = $pixelbin->assets->createSignedUrlV2(
        name: "filename",
        path: "path/to/containing/folder",
        format: "jpeg",
        access: AccessEnum::PUBLIC_READ,
        tags: ["tag1","tag2"],
        metadata: new stdClass(),
        overwrite: false,
        filenameOverride: true,
        expiry: 3000
    );

    // Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

| Argument | Type | Required | Description |
| --------- | ----- | -------- | ----------- |
| name | string | no | name of the file |
| path | string | no | Path of containing folder. |
| format | string | no | Format of the file |
| access | AccessEnum | no | Access level of asset, can be either `public-read` or `private` |
| tags | array | no | Tags associated with the file. |
| metadata | object | no | Metadata associated with the file. |
| overwrite | bool | no | Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`. |
| filenameOverride | bool | no | If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised. |
| expiry | int | no | Expiry time in seconds for the signed URL. Defaults to 3000 seconds. |

For the given asset details, a presigned URL will be generated, which can be then used to upload your asset in chunks via multipart upload.

*Returned Response:*

[SignedUploadV2Response](#signeduploadv2response)

Success

<details>
<summary><i>&nbsp;Example:</i></summary>

```json
{
  "presignedUrl": {
    "url": "https://api.pixelbin.io/service/public/assets/v1.0/signed-multipart?pbs=8b49e6cdd446be379aa4396e1a&pbe=1700600070390&pbt=92661&pbo=143209&pbu=5fe187e8-8649-4546-9a28-ff551839e0f5",
    "fields": {
      "x-pixb-meta-assetdata": "{\"orgId\":1,\"type\":\"file\",\"name\":\"filename.jpeg\",\"path\":\"\",\"fileId\":\"filename.jpeg\",\"format\":\"jpeg\",\"s3Bucket\":\"erase-erase-erasebg-assets\",\"s3Key\":\"uploads/floral-sun-9617c8/original/a34f1d3-28bf-489c-9aff-cc549ac9e003.jpeg\",\"access\":\"public-read\",\"tags\":[],\"metadata\":{\"source\":\"signedUrl\",\"publicUploadId\":\"5fe187e8-8649-4546-9a28-ff551839e0f5\"},\"overwrite\":false,\"filenameOverride\":false}"
    }
  }
}
```

</details>

### Schemas

#### folderItem

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| _id | string |  no  | Id of the folder item |
| orgId | int |  no  | Organization Id |
| name | string |  no  | Name of the folder item |
| path | string |  no  | Path of the folder item |
| type | string |  no  | Type of the item. `file` or `folder` |

#### exploreItem

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| _id | string |  no  | id of the exploreItem |
| orgId | int |  no  | Organization Id |
| name | string |  no  | name of the item |
| type | string |  no  | Type of item whether `file` or `folder` |
| path | string |  no  | Path of the folder item |
| fileId | string |  no  | FileId associated with the item. `path`+`name` |
| format | string |  no  | Format of the file |
| size | int |  no  | Size of the file in bytes |
| access | AccessEnum |  no  | Access level of asset, can be either `public-read` or `private` |
| s3Bucket | string |  no  | Bucket Name |
| s3Key | string |  no  | s3 path of file |

#### page

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| type | string |  yes  | Type of page |
| size | int |  yes  | Number of items on the page |
| current | int |  yes  | Current page number. |
| hasNext | bool |  yes  | Whether the next page exists. |
| itemTotal | int |  yes  | Total number of items. |

#### exploreResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| items | array |  yes  | exploreItems in current page |
| page | page |  yes  | page details |

#### ListFilesResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| items | array |  no  | exploreItems in current page |
| page | page |  no  | page details |

#### FileUploadRequest

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| file | mixed |  yes  | Asset file |
| path | string |  no  | Path where you want to store the asset |
| name | string |  no  | Name of the asset, if not provided name of the file will be used. Note - The provided name will be slugified to make it URL safe |
| access | AccessEnum |  no  | Access level of asset, can be either `public-read` or `private` |
| tags | array |  no  | Asset tags |
| metadata | object |  no  | Asset related metadata |
| overwrite | bool |  no  | Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`. |
| filenameOverride | bool |  no  | If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised. |

#### UrlUploadRequest

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| url | string |  yes  | Asset URL |
| path | string |  no  | Path where you want to store the asset |
| name | string |  no  | Name of the asset, if not provided name of the file will be used. Note - The provided name will be slugified to make it URL safe |
| access | AccessEnum |  no  | Access level of asset, can be either `public-read` or `private` |
| tags | array |  no  | Asset tags |
| metadata | object |  no  | Asset related metadata |
| overwrite | bool |  no  | Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`. |
| filenameOverride | bool |  no  | If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised. |

#### UploadResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| _id | string |  yes  | _id of the item |
| fileId | string |  yes  | FileId associated with the item. path+name |
| name | string |  yes  | name of the item |
| path | string |  yes  | path to the parent folder |
| format | string |  yes  | format of the file |
| size | int |  yes  | size of file in bytes |
| access | AccessEnum |  yes  | Access level of asset, can be either public-read or private |
| tags | array |  no  | tags associated with the item |
| metadata | object |  no  | metadata associated with the item |
| url | string |  no  | url of the item |
| thumbnail | string |  no  | url of item thumbnail |

#### SignedUploadRequest

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| name | string |  no  | name of the file |
| path | string |  no  | Path of the file |
| format | string |  no  | Format of the file |
| access | AccessEnum |  no  | Access level of asset, can be either `public-read` or `private` |
| tags | array |  no  | Tags associated with the file. |
| metadata | object |  no  | Metadata associated with the file. |
| overwrite | bool |  no  | Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`. |
| filenameOverride | bool |  no  | If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised. |

#### SignedUploadResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| s3PresignedUrl | PresignedUrl |  yes  | `signedDetails` for upload with frontend sdk |

#### PresignedUrl

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| url | string |  no  | `presigned url` for upload |
| fields | object |  no  | signed fields to be sent along with request |

#### FilesResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| _id | string |  yes  | _id of the file |
| name | string |  yes  | name of the file |
| path | string |  yes  | path to the parent folder of the file |
| fileId | string |  yes  | FileId associated with the item. `path`+`name` |
| format | string |  yes  | format of the file |
| size | int |  yes  | size of the file in bytes |
| access | AccessEnum |  yes  | Access level of file, can be either `public-read` or `private` |
| isActive | bool |  yes  | Whether the file is active |
| tags | array |  no  | Tags associated with the file |
| metadata | object |  no  | Metadata associated with the file |
| url | string |  no  | url of the file |
| thumbnail | string |  no  | url of the thumbnail of the file |

#### UpdateFileRequest

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| name | string |  no  | Name of the file |
| path | string |  no  | Path of the file |
| access | AccessEnum |  no  | Access level of asset, can be either `public-read` or `private` |
| isActive | bool |  no  | Whether the file is active |
| tags | array |  no  | Tags associated with the file |
| metadata | object |  no  | Metadata associated with the file |

#### FoldersResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| _id | string |  yes  | _id of the folder |
| name | string |  yes  | name of the folder |
| path | string |  yes  | path to the parent folder of the folder |
| isActive | bool |  yes  | whether the folder is active |

#### CreateFolderRequest

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| name | string |  yes  | Name of the folder |
| path | string |  no  | Path of the folder |

#### UpdateFolderRequest

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| isActive | bool |  no  | whether the folder is active |

#### DeleteMultipleFilesRequest

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| ids | array |  yes  | Array of file _ids to delete |

#### Delimiter

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| operationSeparator | string |  no  | separator to separate operations in the url pattern |
| parameterSeparator | string |  no  | separator to separate parameters used with operations in the url pattern |

#### AddCredentialsRequest

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| credentials | object |  yes  | Credentials of the plugin |
| pluginId | string |  yes  | Unique identifier for the plugin this credential belongs to |

#### UpdateCredentialsRequest

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| credentials | object |  yes  | Credentials of the plugin |

#### AddCredentialsResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| credentials | object |  no  |  |

#### GetAncestorsResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| folder | folderItem |  no  |  |
| ancestors | array |  no  |  |

#### AddPresetRequest

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| presetName | string |  yes  | Name of the preset |
| transformation | string |  yes  | A chain of transformations, separated by `~` |
| params | object |  no  | Parameters object for transformation variables |

#### AddPresetResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| presetName | string |  no  | Name of the preset |
| transformation | string |  no  | A chain of transformations, separated by `~` |
| params | object |  no  | Parameters object for transformation variables |
| archived | bool |  no  | Indicates if the preset has been archived |
| orgId | int |  no  | Organization Id |
| isActive | bool |  no  | Indicates if the preset is active |
| createdAt | string |  no  | Preset creation ISO timestamp |
| updatedAt | string |  no  | Preset update ISO timestamp |

#### UpdatePresetRequest

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| archived | bool |  yes  | Indicates if the preset has been archived |

#### GetPresetsResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| items | array |  yes  |  |
| page | page |  yes  | page details |

#### TransformationModuleResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| identifier | string |  no  | identifier for the plugin type |
| name | string |  no  | name of the plugin |
| description | string |  no  | description of the plugin |
| credentials | object |  no  | credentials, if any, associated with the plugin |
| operations | array |  no  | supported operations in the plugin |
| enabled | bool |  no  | whether the plugin is enabled |

#### TransformationModulesResponse

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| delimiters | Delimiter |  no  | Delimiter for parsing plugin schema |
| plugins | object |  no  | Transformations currently supported by the pixelbin |
| presets | array |  no  | List of saved presets |

#### SignedUploadRequestV2

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| name | string |  no  | name of the file |
| path | string |  no  | Path of containing folder. |
| format | string |  no  | Format of the file |
| access | AccessEnum |  no  | Access level of asset, can be either `public-read` or `private` |
| tags | array |  no  | Tags associated with the file. |
| metadata | object |  no  | Metadata associated with the file. |
| overwrite | bool |  no  | Overwrite flag. If set to `true` will overwrite any file that exists with same path, name and type. Defaults to `false`. |
| filenameOverride | bool |  no  | If set to `true` will add unique characters to name if asset with given name already exists. If overwrite flag is set to `true`, preference will be given to overwrite flag. If both are set to `false` an error will be raised. |
| expiry | int |  no  | Expiry time in seconds for the signed URL. Defaults to 3000 seconds. |

#### SignedUploadV2Response

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| presignedUrl | PresignedUrlV2 |  yes  | Presigned URL for uploading asset in chunks |

#### PresignedUrlV2

| Properties | Type | Nullable | Description |
| ---------- | ---- | -------- | ----------- |
| url | string |  no  | Presigned URL for uploading asset in chunks |
| fields | object |  no  | signed fields to be sent along with request |

### Enums

#### AccessEnum

Type : string

| Name | Value | Description |
| ---- | ----- | ----------- |
| public-read | public-read | public-read |
| private | private | private |

---
