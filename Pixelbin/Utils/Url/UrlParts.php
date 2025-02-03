<?php

namespace Pixelbin\Utils\Url {
    use Pixelbin\Common\Exceptions\PDKInvalidUrlError;

    class UrlParts
    {
        public static function getUrlParts(string $pixelbinUrl, UrlConfig $config): UrlObj
        {
            $parsedUrl = parse_url($pixelbinUrl);
            $urlDetails = new UrlObj();
            $urlDetails->protocol = $parsedUrl['scheme'];
            $urlDetails->host = $parsedUrl['host'];
            $urlDetails->search = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';
            $urlDetails->version = "v1";
            $urlDetails->worker = false;
            $urlDetails->workerPath = "";

            $parts = explode("/", $parsedUrl['path']);

            if ($config->isCustomDomain ?? false) {
                // parsing custom domain urls
                if (RegexUtils::test(RegexUtils::VERSION_2_REGEX, $parts[1])) {
                    $urlDetails->version = array_splice($parts, 1, 1)[0];
                } else {
                    throw new PDKInvalidUrlError("Invalid pixelbin url. Please make sure the url is correct.");
                }

                if (RegexUtils::test(RegexUtils::CUSTOM_DOMAIN_REGEX['URL_WITH_WORKER_AND_ZONE'], implode("/", $parts))) {
                    $urlDetails->zone = array_splice($parts, 1, 1)[0];
                    $urlDetails->pattern = "";
                    $urlDetails->filePath = "";
                    $urlDetails->worker = true;
                    $urlDetails->workerPath = implode("/", array_slice($parts, 2));
                } else if (RegexUtils::test(RegexUtils::CUSTOM_DOMAIN_REGEX['URL_WITH_WORKER'], implode("/", $parts))) {
                    $urlDetails->pattern = "";
                    $urlDetails->filePath = "";
                    $urlDetails->worker = true;
                    $urlDetails->workerPath = implode("/", array_slice($parts, 2));
                } else if (RegexUtils::test(RegexUtils::CUSTOM_DOMAIN_REGEX['URL_WITH_ZONE'], implode("/", $parts))) {
                    $urlDetails->zone = array_splice($parts, 1, 1)[0];
                    $urlDetails->pattern = array_splice($parts, 1, 1)[0];
                    $urlDetails->filePath = implode("/", array_slice($parts, 1));
                } else if (RegexUtils::test(RegexUtils::CUSTOM_DOMAIN_REGEX['URL_WITHOUT_ZONE'], implode("/", $parts))) {
                    $urlDetails->pattern = array_splice($parts, 1, 1)[0];
                    $urlDetails->filePath = implode("/", array_slice($parts, 1));
                } else {
                    throw new PDKInvalidUrlError("Invalid pixelbin url. Please make sure the url is correct.");
                }
            } else {
                // parsing pixelbin urls
                if (RegexUtils::test(RegexUtils::VERSION_2_REGEX, $parts[1])) {
                    $urlDetails->version = array_splice($parts, 1, 1)[0];
                }

                if (count($parts) < 2 || empty($parts[1]) || strlen($parts[1]) < 3) {
                    throw new PDKInvalidUrlError("Invalid pixelbin url. Please make sure the url is correct.");
                }

                if (RegexUtils::test(RegexUtils::PIXELBIN_DOMAIN_REGEX['URL_WITH_WORKER_AND_ZONE'], implode("/", $parts))) {
                    $urlDetails->cloudName = array_splice($parts, 1, 1)[0];
                    $urlDetails->zone = array_splice($parts, 1, 1)[0];
                    $urlDetails->pattern = "";
                    $urlDetails->filePath = "";
                    $urlDetails->worker = true;
                    $urlDetails->workerPath = implode("/", array_slice($parts, 2));
                } else if (RegexUtils::test(RegexUtils::PIXELBIN_DOMAIN_REGEX['URL_WITH_WORKER'], implode("/", $parts))) {
                    $urlDetails->cloudName = array_splice($parts, 1, 1)[0];
                    $urlDetails->pattern = "";
                    $urlDetails->filePath = "";
                    $urlDetails->worker = true;
                    $urlDetails->workerPath = implode("/", array_slice($parts, 2));
                } else if (RegexUtils::test(RegexUtils::PIXELBIN_DOMAIN_REGEX['URL_WITH_ZONE'], implode("/", $parts))) {
                    $urlDetails->cloudName = array_splice($parts, 1, 1)[0];
                    $urlDetails->zone = array_splice($parts, 1, 1)[0];
                    $urlDetails->pattern = array_splice($parts, 1, 1)[0];
                    $urlDetails->filePath = implode("/", array_slice($parts, 1));
                } else if (RegexUtils::test(RegexUtils::PIXELBIN_DOMAIN_REGEX['URL_WITHOUT_ZONE'], implode("/", $parts))) {
                    $urlDetails->cloudName = array_splice($parts, 1, 1)[0];
                    $urlDetails->pattern = array_splice($parts, 1, 1)[0];
                    $urlDetails->filePath = implode("/", array_slice($parts, 1));
                } else {
                    throw new PDKInvalidUrlError("Invalid pixelbin url. Please make sure the url is correct.");
                }
            }

            return $urlDetails;
        }
    }
}
