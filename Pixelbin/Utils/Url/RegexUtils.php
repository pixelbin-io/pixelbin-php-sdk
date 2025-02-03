<?php

namespace Pixelbin\Utils\Url {
    class RegexUtils
    {
        public const VERSION_2_REGEX = "/^v[1-2]$/";
        public const ZONE_SLUG = "/([a-zA-Z0-9_-]{6})/";

        public const PIXELBIN_DOMAIN_REGEX = [
            'URL_WITH_ZONE' => '/^\/([a-zA-Z0-9_-]*)\/([a-zA-Z0-9_-]{6})\/(.+)\/(.*)$/',
            'URL_WITHOUT_ZONE' => '/^\/([a-zA-Z0-9_-]*)\/(.+)\/(.*)/',
            'URL_WITH_WORKER_AND_ZONE' => '/^\/([a-zA-Z0-9_-]*)\/([a-zA-Z0-9_-]{6})\/wrkr\/(.*)$/',
            'URL_WITH_WORKER' => '/^\/([a-zA-Z0-9_-]*)\/wrkr\/(.*)$/'
        ];

        public const CUSTOM_DOMAIN_REGEX = [
            'URL_WITH_ZONE' => '/^\/([a-zA-Z0-9_-]{6})\/(.+)\/(.*)$/',
            'URL_WITHOUT_ZONE' => '/^\/(.+)\/(.*)/',
            'URL_WITH_WORKER_AND_ZONE' => '/^\/([a-zA-Z0-9_-]{6})\/wrkr\/(.*)$/',
            'URL_WITH_WORKER' => '/^\/wrkr\/(.*)$/',
        ];

        public static function test($pattern, $input)
        {
            return preg_match($pattern, $input) === 1;
        }
    }
}
