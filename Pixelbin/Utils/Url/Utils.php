<?php

namespace Pixelbin\Utils\Url {
    use Pixelbin\Common\Exceptions;

    class Utils
    {
        const OPERATION_SEPARATOR = "~";
        const PARAMETER_SEPARATOR = ",";
        const PARAMETER_LINK = ":";

        public static function splice($source, $start, $deleteCount)
        {
            $items = array_slice($source, $start, $deleteCount);
            array_splice($source, $start, $deleteCount);
            return $items;
        }

        public static function get_url_from_obj(UrlObj $obj, UrlConfig $config)
        {
            if (empty($obj->baseUrl)) {
                $obj->baseUrl = "https://cdn.pixelbin.io";
            }

            if (!$obj->isCustomDomain && empty($obj->cloudName)) {
                throw new Exceptions\PDKIllegalArgumentError("key cloudName should be defined");
            }

            if ($obj->isCustomDomain && !empty($obj->cloudName)) {
                throw new Exceptions\PDKIllegalArgumentError("key cloudName is not valid for custom domains");
            }

            if (!$obj->worker && empty($obj->filePath)) {
                throw new Exceptions\PDKIllegalArgumentError("key filePath should be defined");
            }

            if ($obj->worker && empty($obj->workerPath)) {
                throw new Exceptions\PDKIllegalArgumentError("key workerPath should be defined");
            }

            if ($obj->worker) {
                $obj->pattern = "wrkr";
            } else {
                $obj->pattern = self::get_pattern_from_transformations($obj->transformations, $config) ?? "original";
            }

            if (empty($obj->version) || !RegexUtils::test(RegexUtils::VERSION_2_REGEX, $obj->version)) {
                $obj->version = "v2";
            }

            if (empty($obj->zone) || !RegexUtils::test(RegexUtils::ZONE_SLUG, $obj->zone)) {
                $obj->zone = "";
            }

            $urlArr = array_filter([$obj->baseUrl, $obj->version, $obj->cloudName, $obj->zone, $obj->pattern]);

            if ($obj->worker) {
                $urlArr[] = $obj->workerPath;
            } else {
                $urlArr[] = $obj->filePath;
            }

            $queryArr = [];
            if ($obj->options !== null) {
                if (!empty($obj->options->dpr)) {
                    $parsedDpr = self::parse_dpr($obj->options->dpr);
                    $queryArr[] = "dpr=" . $parsedDpr;
                }
                if (!empty($obj->options->f_auto)) {
                    self::validate_f_auto($obj->options->f_auto);
                    $queryArr[] = "f_auto=" . strtolower($obj->options->f_auto ? "true" : "false");
                }
            }

            $urlStr = implode("/", $urlArr);

            if (count($queryArr) > 0) {
                $urlStr .= "?" . implode("&", $queryArr);
            }

            return $urlStr;
        }

        private static function get_parts_from_url(string $url, UrlConfig $config): UrlObj
        {
            $parts = UrlParts::getUrlParts($url, $config);
            $queryObj = self::process_query_params($parts);

            return new UrlObj(
                protocol: $parts->protocol,
                host: $parts->host,
                search: $parts->search,
                workerPath: $parts->workerPath,
                version: $parts->version,
                baseUrl: "{$parts->protocol}://{$parts->host}",
                filePath: $parts->filePath,
                pattern: $parts->pattern,
                cloudName: $parts->cloudName,
                zone: $parts->zone,
                worker: $parts->worker,
                isCustomDomain: $parts->isCustomDomain,
                options: $queryObj
            );
        }

        public static function get_obj_from_url(string $url, UrlConfig $config, bool $flatten = false)
        {
            $parts = self::get_parts_from_url($url, $config);
            try {
                $parts->transformations = !empty($parts->pattern)
                    ? self::get_transformation_details_from_pattern($parts->pattern, $url, $config, $flatten)
                    : [];
            } catch (\Exception $e) {
                throw new Exceptions\PDKInvalidUrlError("Error Processing url. Please check the url is correct");
            }
            return $parts;
        }

        private static function remove_leading_dash($str): string
        {
            return ltrim($str, '-');
        }

        private static function get_params_list($dSplit, $prefix)
        {
            $splitRes = explode("(", $dSplit);
            if (count($splitRes) < 2)
                return [];

            return explode(self::PARAMETER_SEPARATOR, self::remove_leading_dash(str_replace($prefix, "", str_replace(")", "", $splitRes[1]))));
        }

        private static function get_params_object($paramsList): array
        {
            $parameters = [];
            foreach ($paramsList as $item) {
                if (strpos($item, ':') !== false) {
                    [$param, $val] = explode(':', $item, 2);
                    if (!empty($param)) {
                        $parameters[] = ['key' => $param, 'value' => $val];
                    }
                }
            }
            return $parameters;
        }

        private static function get_operation_details_from_operation($dSplit): UrlTransformation
        {
            $fullFnName = explode("(", $dSplit)[0];

            if (strpos($dSplit, 'p:') === 0) {
                if (count(explode(':', $fullFnName, 2)) < 2)
                    throw new \Exception;
                [$pluginId, $operationName] = explode(':', $fullFnName, 2);
            } else {
                if (count(explode('.', $fullFnName, 2)) < 2)
                    throw new \Exception;

                [$pluginId, $operationName] = explode('.', $fullFnName, 2);
            }

            $values = [];
            if ($pluginId === 'p') {
                if (str_contains($dSplit, "("))
                    $values = self::get_params_object(self::get_params_list($dSplit, ''));
            } else {
                if (str_contains($dSplit, "("))
                    $values = self::get_params_object(self::get_params_list($dSplit, ''));
                else
                    throw new \Exception;
            }

            $transformation = new UrlTransformation(plugin: $pluginId, name: $operationName);
            if (count($values) > 0)
                $transformation->values = $values;

            return $transformation;
        }

        private static function get_transformation_details_from_pattern(string $pattern, string $url, UrlConfig $config, bool $flatten = false): array
        {
            if ($pattern === 'original') {
                return [];
            }

            $dSplit = explode($config->operationSeparator, $pattern);
            $dSplit = array_filter($dSplit);

            $opts = array_map(function ($x) {
                return self::get_operation_details_from_operation($x);
            }, $dSplit);

            return $opts;
        }

        private static function get_pattern_from_transformations(array|null $transformationList, UrlConfig $config): string|null
        {
            if (!empty($transformationList) && count($transformationList) > 0) {
                $transformationListStr = array_filter(array_map(function ($o) use ($config) {
                    if (!empty($o->name)) {
                        $o->values = !empty($o->values) ? $o->values : [];
                        $paramlist = array_map(function ($items) use ($o) {
                            if (!isset($items['key']) || empty($items['key'])) {
                                throw new Exceptions\PDKIllegalArgumentError("key not specified in '{$o->name}'");
                            }
                            if (!isset($items['value']) || empty($items['value'])) {
                                throw new Exceptions\PDKIllegalArgumentError("value not specified for key '{$items['key']}' in '{$o->name}'");
                            }
                            return "{$items['key']}:{$items['value']}";
                        }, $o->values);
                        $paramStr = implode($config->parameterSeparator, $paramlist);
                        if ($o->plugin === 'p') {
                            return !empty($paramStr) ? "{$o->plugin}:{$o->name}({$paramStr})" : "{$o->plugin}:{$o->name}";
                        }
                        return "{$o->plugin}.{$o->name}({$paramStr})";
                    }
                    return null;
                }, $transformationList));

                if (count($transformationListStr) > 0) {
                    return implode($config->operationSeparator, $transformationListStr);
                }
            }
            return null;
        }

        private static function parse_dpr(string $dpr): string
        {
            if ($dpr === 'auto') {
                return $dpr;
            }

            if (!is_numeric($dpr) || $dpr < 0.1 || $dpr > 5.0) {
                throw new Exceptions\PDKIllegalQueryParameterError("DPR value should be numeric and should be between 0.1 to 5.0");
            }

            return number_format($dpr, 1);
        }

        private static function checkBool($string)
        {
            $string = strtolower($string);
            return (in_array($string, array("true", "false", "1", "0", "yes", "no"), true));
        }

        private static function validate_f_auto($f_auto): void
        {
            if (!Utils::checkBool($f_auto)) {
                throw new Exceptions\PDKIllegalQueryParameterError("F_auto value should be boolean");
            }
        }

        private static function process_query_params($urlParts): UrlObjOptions
        {
            $queryObj = new UrlObjOptions();
            if (!empty($urlParts->search)) {
                $queryParams = explode('&', ltrim($urlParts->search, '?'));
                foreach ($queryParams as $param) {
                    list($key, $value) = explode('=', $param, 2);
                    if ($key === 'dpr') {
                        $dpr = self::parse_dpr($value);
                        $queryObj->dpr = $dpr;
                    }
                    if ($key === 'f_auto') {
                        self::validate_f_auto(strtolower($value));
                        $queryObj->f_auto = $value;
                    }
                }
            }
            return $queryObj;
        }
    }
}
