<?php

namespace Pixelbin\Utils {

    use Pixelbin\Common\Exceptions;

    class Security
    {
        public static function signURL(string $url, float $expirySeconds, string $accessKey, string $token)
        {
            return self::generateSignedURL($url, $expirySeconds, $accessKey, $token);
        }

        private static function encodeURI($str)
        {
            // Characters that `encodeURI` would not encode
            $unencodedChars = ['/', ':', '?', '=', '&', '#'];

            // Perform URL encoding on the entire string, except for reserved characters
            return preg_replace_callback('/[^A-Za-z0-9\-._~:\/?&=#]/', function ($matches) {
                return rawurlencode($matches[0]);
            }, $str);
        }

        private static function generateSignature(string $urlPath, int $expiryTimestamp, string $key)
        {
            if (substr($urlPath, 0, 1) === "/") {
                $urlPath = substr($urlPath, 1);
            }

            $urlPath = self::encodeURI($urlPath);

            $hash = hash_hmac("sha256", $urlPath . (string) $expiryTimestamp, $key);

            return $hash;
        }

        private static function generateSignedURL(string $url, float $expirySeconds, string $accessKey, string $token)
        {
            if (empty($url) || empty($accessKey) || empty($token) || empty($expirySeconds)) {
                throw new Exceptions\PDKIllegalArgumentError("url, accessKey, token & expirySeconds are required for generating signed URL");
            }

            if (!(gettype($expirySeconds) === "double")) {
                throw new Exceptions\PDKIllegalArgumentError("Expected expirySeconds to be a Number. Got " . gettype($expirySeconds) . " instead");
            }

            $urlObj = parse_url($url);
            $urlPath = $urlObj["path"] ?? "";
            $urlPath .= isset($urlObj["query"]) ? "?" . $urlObj["query"] : "";
            $urlQuery = [];
            if (isset($urlObj["query"])) {
                parse_str($urlObj["query"], $urlQuery);
            }

            if (isset($urlObj["query"]) && strpos($urlObj["query"], "pbs=") !== false) {
                throw new Exceptions\PDKIllegalArgumentError("URL already has a signature");
            }

            $expiryTimestamp = time() + $expirySeconds;

            $signature = self::generateSignature($urlPath, $expiryTimestamp, $token);

            $queryParams = [
                "pbs" => $signature,
                "pbe" => $expiryTimestamp,
                "pbt" => $accessKey,
                ...$urlQuery
            ];

            $queryString = http_build_query($queryParams);

            return $urlObj["scheme"] . "://" . $urlObj["host"] . $urlObj["path"] . "?" . $queryString;
        }
    }
}
