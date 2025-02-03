# Pixelbin Backend SDK for PHP

Pixelbin Backend SDK for PHP helps you integrate the core Pixelbin features with your application.

## Getting Started

Getting started with Pixelbin Backend SDK for PHP

### Installation

```bash
composer require pixelbin/pixelbin
```

---

### Usage

#### Quick Example

```php
<?php

// Import the PixelbinConfig and PixelbinClient
use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

// Create a config with your API_TOKEN
$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_TOKEN",
    "integrationPlatform"=> "YourAppName/1.0 (AppPlatform/2.0)", // this is optional
]);

// Create a pixelbin instance
$pixelbin = new PixelbinClient($config);

// Method call
try {
    // List the assets stored on your organization's Pixelbin Storage
    $result = $pixelbin->assets->listFiles();
    # Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

## Uploader

### upload

Uploads a file to PixelBin with greater control over the upload process.

#### Arguments

| Argument            | Type                                                        | Required | Description                                                                                                                                                                                                                                           |
| ------------------- | ----------------------------------------------------------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `file`              | `StreamInterface` or `buffer(string)`                       | yes      | The file to be uploaded.                                                                                                                                                                                                                              |
| `name`              | `string`                                                    | no       | Name of the file.                                                                                                                                                                                                                                     |
| `path`              | `string`                                                    | no       | Path of the containing folder.                                                                                                                                                                                                                        |
| `format`            | `string`                                                    | no       | Format of the file.                                                                                                                                                                                                                                   |
| `access`            | [AccessEnum](./documentation/platform/ASSETS.md#accessenum) | no       | Access level of the asset, can be either `'public-read'` or `'private'`.                                                                                                                                                                              |
| `tags`              | `list[string]`                                              | no       | Tags associated with the file.                                                                                                                                                                                                                        |
| `metadata`          | `object`                                                    | no       | Metadata associated with the file.                                                                                                                                                                                                                    |
| `overwrite`         | `bool`                                                      | no       | Overwrite flag. If set to `True`, will overwrite any file that exists with the same path, name, and type. Defaults to `False`.                                                                                                                        |
| `filenameOverride`  | `bool`                                                      | no       | If set to `True`, will add unique characters to the name if an asset with the given name already exists. If the overwrite flag is set to `True`, preference will be given to the overwrite flag. If both are set to `False`, an error will be raised. |
| `expiry`            | `int`                                                       | no       | Expiry time in seconds for the underlying signed URL. Defaults to 3000 seconds.                                                                                                                                                                       |
| `uploadOptions`     | `array`                                                     | no       | Additional options for fine-tuning the upload process. Default: `{ chunk_size: 10 * 1024 * 1024, max_retries: 2, concurrency: 3, exponential_factor: 2 }`.                                                                                            |
| `chunkSize`         | `int`                                                       | no       | Size of each chunk to upload. Default is 10 megabytes.                                                                                                                                                                                                |
| `maxRetries`        | `int`                                                       | no       | Maximum number of retries if an upload fails. Default is 2 retries.                                                                                                                                                                                   |
| `concurrency`       | `int`                                                       | no       | Number of concurrent chunk upload tasks. Default is 3 concurrent chunk uploads.                                                                                                                                                                       |
| `exponentialFactor` | `float`                                                     | no       | The exponential factor for retry delay. Default is 2.                                                                                                                                                                                                 |

#### Returns

`array`: On success, returns a dictionary containing the details of the uploaded file.

| Property     | Description                                                  | Example                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| ------------ | ------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `orgId`      | Organization ID                                              | `5320086`                                                                                                                                                                                                                                                                                                                                                                                                                                  |
| `type`       | Type of the uploaded entity                                  | `file`                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| `name`       | Name of the file                                             | `testfile.jpeg`                                                                                                                                                                                                                                                                                                                                                                                                                            |
| `path`       | Path of the containing folder                                | `/path/to/image.jpeg`                                                                                                                                                                                                                                                                                                                                                                                                                      |
| `fileId`     | ID of the file                                               | `testfile.jpeg`                                                                                                                                                                                                                                                                                                                                                                                                                            |
| `access`     | Access level of the asset, can be `public-read` or `private` | `public-read`                                                                                                                                                                                                                                                                                                                                                                                                                              |
| `tags`       | Tags associated with the file                                | `["tag1", "tag2"]`                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `metadata`   | Metadata associated with the file                            | `{"source":"", "publicUploadId":""}`                                                                                                                                                                                                                                                                                                                                                                                                       |
| `format`     | File format                                                  | `jpeg`                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| `assetType`  | Type of asset                                                | `image`                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `size`       | File size (in bytes)                                         | `37394`                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `width`      | File width (in pixels)                                       | `720`                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| `height`     | File height (in pixels)                                      | `450`                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| `context`    | File metadata and additional context                         | `{"steps":[],"req":{"headers":{},"query":{}},"meta":{"format":"png","size":195337,"width":812,"height":500,"space":"srgb","channels":4,"depth":"uchar","density":144,"isProgressive":false,"resolutionUnit":"inch","hasProfile":true,"hasAlpha":true,"extension":"jpeg","contentType":"image/png","assetType":"image","isImageAsset":true,"isAudioAsset":false,"isVideoAsset":false,"isRawAsset":false,"isTransformationSupported":true}}` |
| `isOriginal` | Flag indicating if the file is original                      | `true`                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| `_id`        | Record ID                                                    | `a0b0b19a-d526-4xc07-ae51-0xxxxxx`                                                                                                                                                                                                                                                                                                                                                                                                         |
| `url`        | URL of the uploaded file                                     | `https://cdn.pixelbin.io/v2/user-e26cf3/original/testfile.jpeg`                                                                                                                                                                                                                                                                                                                                                                            |

#### Example Usage

##### Uploading a Buffer

```php
<?php

require_once(__DIR__ . "/vendor/autoload.php");
use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;
use Pixelbin\Platform\Enums\AccessEnum;

// Create a config with your API_TOKEN
$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_TOKEN",
    "integrationPlatform"=> "YourAppName/1.0 (AppPlatform/2.0)", // this is optional
]);

// Create a PixelBin client instance
$pixelbin = new PixelbinClient($config);

// Sync method call
try {
    // Read the file into a buffer
    $buffer = file_get_contents("myimage.png");

    $result = $pixelbin->uploader->upload(
        file: $buffer,
        name: 'myimage',
        path: 'folder',
        format: 'png',
        access: AccessEnum::PUBLIC_READ,
        tags: [],
        metadata: (object) [],
        overwrite: true,
        filenameOverride: true,
        expiry: 1500,
        uploadOptions: [
            'chunkSize' => 5 * 1024 * 1024,  // 5MB
            'concurrency' => 3,              // 2 concurrent chunk uploads
            'maxRetries' => 2,               // 1 retry for errors that can be retried
            'exponentialFactor' => 2,        // Exponential factor for retries
        ]
    );

    print_r($result["url"]);
    // "https://cdn.pixelbin.io/v2/mycloudname/original/folder/myimage.png"
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

##### Uploading a Stream

```php
<?php

require_once(__DIR__ . "/vendor/autoload.php");
use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;
use Pixelbin\Platform\Enums\AccessEnum;
use GuzzleHttp\Psr7\Stream;

// Create a config with your API_TOKEN
$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_TOKEN",
    "integrationPlatform"=> "YourAppName/1.0 (AppPlatform/2.0)", // this is optional
]);

// Create a PixelBin client instance
$pixelbin = new PixelbinClient($config);

// Sync method call
try {
    if ($stream = fopen('../2.jpeg', 'r')) {
        // Read the file into a stream
        $fileStream = new Stream($stream);

        $result = $pixelbin->uploader->upload(
            file: $fileStream,
            name: 'myimage',
            path: 'folder',
            format: 'png',
            access: AccessEnum::PUBLIC_READ,
            tags: [],
            metadata: (object) [],
            overwrite: true,
            filenameOverride: true,
            expiry: 1500,
            uploadOptions: [
                'chunkSize' => 5 * 1024 * 1024,  // 5MB
                'concurrency' => 3,              // 2 concurrent chunk uploads
                'maxRetries' => 2,               // 1 retry for errors that can be retried
                'exponentialFactor' => 2,        // Exponential factor for retries
            ]
        );

        print_r($result["url"]);
        // "https://cdn.pixelbin.io/v2/mycloudname/original/folder/myimage.png"
        fclose($stream);
    }
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

## Security Utils

### For generating Signed URLs

Generate a signed Pixelbin URL

| Parameter                | Description                                          | Example                                                                                    |
| ------------------------ | ---------------------------------------------------- | ------------------------------------------------------------------------------------------ |
| `url` (string)           | A valid Pixelbin URL to be signed                    | `https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg` |
| `expirySeconds` (number) | Number of seconds the signed URL should be valid for | `20`                                                                                       |
| `accessKey` (string)     | Access key of the token used for signing             | `00000000-0000-0000-0000-000000000000`                                                     |
| `token` (string)         | Value of the token used for signing                  | `dummy-token`                                                                              |

Example:

```php
<?php

use Pixelbin\Utils\Security;

$signedUrl = Security::signURL(
    "https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg", // url
    20, // expirySeconds
    "0b55aaff-d7db-45f0-b556-9b45a6f2200e", // accessKey
    "dummy-token", // token
);
// signedUrl
// https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg?pbs=8eb6a00af74e57967a42316e4de238aa88d92961649764fad1832c1bff101f25&pbe=1695635915&pbt=0b55aaff-d7db-45f0-b556-9b45a6f2200e
```

Usage with custom domain url:

```php
<?php

use Pixelbin\Utils\Security;

$signedUrl = Security::signURL(
    "https://krit.imagebin.io/v2/original/__playground/playground-default.jpeg", // url
    30, // expirySeconds
    "0b55aaff-d7db-45f0-b556-9b45a6f2200e", // accessKey
    "dummy-token", // token
);
// signedUrl
// https://krit.imagebin.io/v2/original/__playground/playground-default.jpeg?pbs=1aef31c1e0ecd8a875b1d3184f324327f4ab4bce419d81d1eb1a818ee5f2e3eb&pbe=1695705975&pbt=0b55aaff-d7db-45f0-b556-9b45a6f2200e
```

## URL Utils

Pixelbin provides url utilities to construct and deconstruct Pixelbin urls.

### url_to_obj

Deconstruct a pixelbin url

| parameter               | description                                                        | example                                                                                               |
| ----------------------- | ------------------------------------------------------------------ | ----------------------------------------------------------------------------------------------------- |
| `url` (string)          | A valid Pixelbin URL                                               | `https://cdn.pixelbin.io/v2/your-cloud-name/z-slug/t.resize(h:100,w:200)~t.flip()/path/to/image.jpeg` |
| `opts` (Object)         | Options for the conversion                                         | Default: `{ isCustomDomain: False }`                                                                  |
| `opts.is_custom_domain` | Indicates if the URL belongs to a custom domain (default: `False`) |                                                                                                       |

**Returns**:

| Property                  | Description                                          | Example                               |
| ------------------------- | ---------------------------------------------------- | ------------------------------------- |
| `baseURL` (string)        | Base path of the URL                                 | `https://cdn.pixelbin.io`             |
| `filePath` (string)       | Path to the file on Pixelbin storage                 | `/path/to/image.jpeg`                 |
| `version` (string)        | Version of the URL                                   | `v2`                                  |
| `cloudName` (string)      | Cloud name from the URL                              | `your-cloud-name`                     |
| `transformations` (array) | A list of transformation objects                     | `[{ "plugin": "t", "name": "flip" }]` |
| `zone` (string)           | Zone slug from the URL                               | `z-slug`                              |
| `pattern` (string)        | Transformation pattern extracted from the URL        | `t.resize(h:100,w:200)~t.flip()`      |
| `worker` (boolean)        | Indicates if the URL is a URL Translation Worker URL | `False`                               |
| `workerPath` (string)     | Input path to a URL Translation Worker               | `resize:w200,h400/folder/image.jpeg`  |
| `options` (Object)        | Query parameters added, such as "dpr" and "f_auto"   | `{ dpr: 2.5, f_auto: True}`           |

Example:

```php
<?php

use Pixelbin\Utils\Url;

$pixelbinUrl = "https://cdn.pixelbin.io/v2/your-cloud-name/z-slug/t.resize(h:100,w:200)~t.flip()/path/to/image.jpeg?dpr=2.0&f_auto=true"
$obj = Url::url_to_obj($pixelbinUrl);
// obj
// {
//     "cloudName": "your-cloud-name",
//     "zone": "z-slug",
//     "version": "v2",
//     "options": {
//         "dpr": 2.0,
//         "f_auto": true,
//     },
//     "transformations": [
//         {
//             "plugin": "t",
//             "name": "resize",
//             "values": [
//                 {
//                     "key": "h",
//                     "value": "100"
//                 },
//                 {
//                     "key": "w",
//                     "value": "200"
//                 }
//             ]
//         },
//         {
//             "plugin": "t",
//             "name": "flip",
//         }
//     ],
//     "filePath": "path/to/image.jpeg",
//     "baseUrl": "https://cdn.pixelbin.io"
// }
```

```php
<?php

use Pixelbin\Utils\Url;

$customDomainUrl = "https://xyz.designify.media/v2/z-slug/t.resize(h:100,w:200)~t.flip()/path/to/image.jpeg";
$obj = Url::url_to_obj(customDomainUrl, opts={ is_custom_domain: True })
// obj
// {
//     "zone": "z-slug",
//     "version": "v2",
//     "transformations": [
//         {
//             "plugin": "t",
//             "name": "resize",
//             "values": [
//                 {
//                     "key": "h",
//                     "value": "100"
//                 },
//                 {
//                     "key": "w",
//                     "value": "200"
//                 }
//             ]
//         },
//         {
//             "plugin": "t",
//             "name": "flip",
//         }
//     ],
//     "filePath": "path/to/image.jpeg",
//     "baseUrl": "https://xyz.designify.media",
//     "wrkr": False,
//     "workerPath": "",
//     "options": {}
// }
```

```php
<?php

use Pixelbin\Utils\Url;

$workerUrl = "https://cdn.pixelbin.io/v2/your-cloud-name/z-slug/wrkr/resize:h100,w:200/folder/image.jpeg";

$obj = Url::url_to_obj(workerUrl)
// obj
// {
//     "cloudName": "your-cloud-name",
//     "zone": "z-slug",
//     "version": "v2",
//     "transformations": [],
//     "filePath": "",
//     "worker": True,
//     "workerPath": "resize:h100,w:200/folder/image.jpeg",
//     "baseUrl": "https://cdn.pixelbin.io"
//     "options": {}
// }
```

### obj_to_url

Converts the extracted url obj to a Pixelbin url.

| Property                   | Description                                          | Example                               |
| -------------------------- | ---------------------------------------------------- | ------------------------------------- |
| `cloudName` (string)       | The cloudname extracted from the URL                 | `your-cloud-name`                     |
| `zone` (string)            | 6 character zone slug                                | `z-slug`                              |
| `version` (string)         | CDN API version                                      | `v2`                                  |
| `transformations` (array)  | Extracted transformations from the URL               | `[{ "plugin": "t", "name": "flip" }]` |
| `filePath` (string)        | Path to the file on Pixelbin storage                 | `/path/to/image.jpeg`                 |
| `baseUrl` (string)         | Base URL                                             | `https://cdn.pixelbin.io/`            |
| `isCustomDomain` (boolean) | Indicates if the URL is for a custom domain          | `False`                               |
| `worker` (boolean)         | Indicates if the URL is a URL Translation Worker URL | `False`                               |
| `workerPath` (string)      | Input path to a URL Translation Worker               | `resize:w200,h400/folder/image.jpeg`  |
| `options` (Object)         | Query parameters added, such as "dpr" and "f_auto"   | `{ "dpr": 2.0, "f_auto": True }`      |

```php
<?php

use Pixelbin\Utils\Url;

$obj = [
    "cloudName" => "your-cloud-name",
    "zone" => "z-slug",
    "version" => "v2",
    "options" => [
        "dpr" => 2.0,
        "f_auto" => true,
    ],
    "transformations" => [
        [
            plugin: "t",
            name: "resize",
            values: [
                [
                    "key" => "h",
                    "value" => "100",
                ],
                [
                    "key" => "w",
                    "value" => "200",
                ],
            ],
        ],
        [
            "plugin" => "t",
            "name" => "flip",
        ],
    ],
    "filePath" => "path/to/image.jpeg",
    "baseUrl" => "https://cdn.pixelbin.io",
];

$url = Url::obj_to_url($obj);
// url
// https://cdn.pixelbin.io/v2/your-cloud-name/z-slug/t.resize(h:100,w:200)~t.flip()/path/to/image.jpeg?dpr=2.0&f_auto=true
```

Usage with custom domain

```php
<?php

use Pixelbin\Utils\Url;

$obj = [
    "zone" => "z-slug",
    "version" => "v2",
    "transformations" => [
        [
            "plugin" => "t",
            "name" => "resize",
            "values" => [
                [
                    "key" => "h",
                    "value" => "100",
                ],
                [
                    "key" => "w",
                    "value" => "200",
                ],
            ],
        ],
        [
            "plugin" => "t",
            "name" => "flip",
        ],
    ],
    "filePath" => "path/to/image.jpeg",
    "baseUrl" => "https://xyz.designify.media",
    "isCustomDomain" => True,
];

$url = Url::obj_to_url($obj); // obj is as shown above
// url
// https://xyz.designify.media/v2/z-slug/t.resize(h:100,w:200)~t.flip()/path/to/image.jpeg
```

Usage with URL Translation Worker

```php
<?php

use Pixelbin\Utils\Url;

$obj = {
    "cloudName" => "your-cloud-name",
    "zone" => "z-slug",
    "version" => "v2",
    "transformations" => [],
    "filePath" => "",
    "worker" => True,
    "workerPath" => "resize:h100,w:200/folder/image.jpeg",
    "baseUrl" => "https://cdn.pixelbin.io",
};

$url = Url::obj_to_url($obj); // obj is as shown above
// url
// https://cdn.pixelbin.io/v2/your-cloud-name/z-slug/wrkr/resize:h100,w:200/folder/image.jpeg
```

## Documentation

- [API docs](documentation/platform/README.md)
