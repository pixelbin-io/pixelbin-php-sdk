<?php

namespace Pixelbin\Utils\Url {

    use Pixelbin\Utils\Url\Utils;
    use Pixelbin\Utils\Url\UrlConfig;

    class Url
    {
        public static UrlConfig $config;

        public static function init()
        {
            self::$config = new UrlConfig(
                operationSeparator: "~",
                parameterSeparator: ","
            );
        }


        public static function url_to_obj(string $url, UrlConfig|null $opts = null): UrlObj
        {
            self::init();
            return Utils::get_obj_from_url(
                url: $url,
                config: new UrlConfig(
                    operationSeparator: Url::$config->operationSeparator,
                    parameterSeparator: Url::$config->parameterSeparator,
                    isCustomDomain: $opts->isCustomDomain ?? false,
                ),
                flatten: false
            );
        }

        public static function obj_to_url(UrlObj $obj): string
        {
            self::init();
            return Utils::get_url_from_obj(obj: $obj, config: Url::$config);
        }
    }
}
