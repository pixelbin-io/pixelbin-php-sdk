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
    "integrationPlatform" => "YourAppName/1.0 (AppPlatform/2.0)", // this is optional
]);

// Create a PixelBin client instance
$pixelbin = new PixelbinClient($config);

// Method call
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
