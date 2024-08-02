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

require_once(__DIR__ . "/vendor/autoload.php");

use Pixelbin\Platform\PixelbinClient;
use Pixelbin\Platform\PixelbinConfig;

// Create client with your API_TOKEN
$config = new PixelbinConfig([
    "domain" => "https://api.pixelbin.io",
    "apiSecret" => "API_TOKEN",
]);

// Create a pixelbin instance
$pixelbin = new PixelbinClient($config);

// Method call
try {
    $result = $pixelbin->assets->listFiles();
    # Use result
    print_r($result);
} catch (Exception $e) {
    print_r($e->getMessage());
}
```

## Utilities

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
