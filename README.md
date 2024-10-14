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

| parameter            | description          | example                                                                                               |
| -------------------- | -------------------- | ----------------------------------------------------------------------------------------------------- |
| pixelbinUrl (string) | A valid pixelbin url | `https://cdn.pixelbin.io/v2/your-cloud-name/z-slug/t.resize(h:100,w:200)~t.flip()/path/to/image.jpeg` |

**Returns**:

| property                | description                            | example                    |
| ----------------------- | -------------------------------------- | -------------------------- |
| cloudName (string)      | The cloudname extracted from the url   | `your-cloud-name`          |
| zone (string)           | 6 character zone slug                  | `z-slug`                   |
| version (string)        | cdn api version                        | `v2`                       |
| options (object)        | optional query parameters              |                            |
| transformations (array) | Extracted transformations from the url |                            |
| filePath                | Path to the file on Pixelbin storage   | `/path/to/image.jpeg`      |
| baseUrl (string)        | Base url                               | `https://cdn.pixelbin.io/` |

Example:

```php
<?php

use Pixelbin\Utils\Url;

$pixelbinUrl = "https://cdn.pixelbin.io/v2/your-cloud-name/z-slug/t.resize(h:100,w:200)~t.flip()/path/to/image.jpeg?dpr=2.0&f_auto=True"
$obj = Url::url_to_obj($pixelbinUrl);
```

Output obj stored in `$obj`:

```json
{
    "cloudName": "your-cloud-name",
    "zone": "z-slug",
    "version": "v2",
    "options": {
        "dpr": 2.0,
        "f_auto": true,
    },
    "transformations": [
        {
            "plugin": "t",
            "name": "resize",
            "values": [
                {
                    "key": "h",
                    "value": "100"
                },
                {
                    "key": "w",
                    "value": "200"
                }
            ]
        },
        {
            "plugin": "t",
            "name": "flip",
        }
    ],
    "filePath": "path/to/image.jpeg",
    "baseUrl": "https://cdn.pixelbin.io"
}
```

### obj_to_url

Converts the extracted url obj to a Pixelbin url.

| property                | description                            | example                    |
| ----------------------- | -------------------------------------- | -------------------------- |
| cloudName (string)      | The cloudname extracted from the url   | `your-cloud-name`          |
| zone (string)           | 6 character zone slug                  | `z-slug`                   |
| version (string)        | cdn api version                        | `v2`                       |
| options (object)        | optional query parameters              |                            |
| transformations (array) | Extracted transformations from the url |                            |
| filePath                | Path to the file on Pixelbin storage   | `/path/to/image.jpeg`      |
| baseUrl (string)        | Base url                               | `https://cdn.pixelbin.io/` |

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
```

Output url stored in `$url`:

```bash
https://cdn.pixelbin.io/v2/your-cloud-name/z-slug/t.resize(h:100,w:200)~t.flip()/path/to/image.jpeg?dpr=2.0&f_auto=True
```

## Documentation

- [API docs](documentation/platform/README.md)
