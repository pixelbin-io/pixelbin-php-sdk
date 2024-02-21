<?php

namespace Pixelbin\Utils {

    use Pixelbin\Common\Exceptions;

    use Exception;

    class Url
    {
        const OPERATION_SEPARATOR = "~";
        const PARAMETER_SEPARATOR = ",";
        const VERSION2_REGEX = "/^v[1-2]\$/";
        const URL_WITH_ZONE = "/^\/([a-zA-Z0-9_-]*)\/([a-zA-Z0-9_-]{6})\/(.+)\/(.*)\$/";
        const URL_WITHOUT_ZONE = "/\/([a-zA-Z0-9_-]*)\/(.+)\/(.*)/";
        const ZONE_SLUG = "/([a-zA-Z0-9_-]{6})/";
        const BASE_URL = "https://cdn.pixelbin.io";
        const ALLOWED_OPTIONAL_PARAMS = ["dpr", "f_auto"];

        public static function url_to_obj(string $url)
        {
            return self::get_obj_from_url($url);
        }

        public static function obj_to_url(array $obj)
        {
            return self::get_url_from_obj($obj);
        }

        public static function get_url_parts(string $pixelbinUrl)
        {
            $parse_url = parse_url($pixelbinUrl);
            $urlDetails = [
                "protocol" => $parse_url["scheme"],
                "host" => $parse_url["host"],
                "search" => !empty($parse_url["query"]) ? $parse_url["query"] : "",
                "version" => "v1",
            ];
            $parts = explode("/", $parse_url["path"]);
            if (!empty(preg_match(self::VERSION2_REGEX, $parts[1]))) {
                $urlDetails["version"] = $parts[1];
                unset($parts[1]);
                $parts = array_values($parts);
            }

            if (strlen($parts[1]) < 3) {
                throw new Exceptions\PixelbinInvalidUrlError("Invalid pixelbin url. Please make sure the url is correct.");
            }

            if (!empty(preg_match(self::URL_WITH_ZONE, implode("/", $parts)))) {
                $urlDetails["cloudName"] = $parts[1];
                unset($parts[1]);
                $parts = array_values($parts);
                $urlDetails["zoneSlug"] = $parts[1];
                unset($parts[1]);
                $parts = array_values($parts);
                $urlDetails["pattern"] = $parts[1];
                unset($parts[1]);
                $parts = array_values($parts);
                $urlDetails["filePath"] = implode("/", array_splice($parts, 1));
            } elseif (!empty(preg_match(self::URL_WITHOUT_ZONE, implode("/", $parts)))) {
                $urlDetails["cloudName"] = $parts[1];
                unset($parts[1]);
                $parts = array_values($parts);
                $urlDetails["pattern"] = $parts[1];
                unset($parts[1]);
                $parts = array_values($parts);
                $urlDetails["filePath"] = implode("/", array_splice($parts, 1));
            } else {
                throw new Exceptions\PixelbinInvalidUrlError("Invalid pixelbin url. Please make sure the url is correct.");
            }
            return $urlDetails;
        }

        public static function get_parts_from_url(string $url)
        {
            $parts = self::get_url_parts($url);
            $queryObj = self::process_query_params($parts);

            $parts["zone"] = null;
            if (array_key_exists("zoneSlug", $parts)) {
                $parts["zone"] = $parts["zoneSlug"];
                unset($parts["zoneSlug"]);
            }
            $parts["baseUrl"] = $parts["protocol"] . "://" . $parts["host"];
            unset($parts["protocol"]);
            unset($parts["host"]);
            $parts["options"] = $queryObj;
            unset($parts["search"]);
            return $parts;
        }

        public static function remove_leading_dash(string $str)
        {
            if (strlen($str) > 0 && $str[0] === "-") {
                return substr($str, 1);
            }
            return $str;
        }

        public static function get_params_list($dSplit, $prefix)
        {

            return explode(",", self::remove_leading_dash(str_replace([")", $prefix], "", explode("(", $dSplit)[1])));
        }

        public static function get_params_object(array $paramsList)
        {
            $params = [];
            foreach ($paramsList as $item) {
                if (str_contains($item, ":")) {
                    [$param, $val] = explode(":", $item);
                    if (!empty($param)) {
                        $params[$param] = $val;
                    }
                }
            }

            if (count($params) > 0) {
                return $params;
            }
            return 0;
        }

        public static function get_operation_details_from_operation($dSplit)
        {
            $fullFnName = explode("(", $dSplit)[0];

            $pluginId = null;

            $operationName = null;
            if (str_starts_with($dSplit, "p:")) {
                [$pluginId, $operationName] = explode(":", $fullFnName);
            } else {
                [$pluginId, $operationName] = explode(".", $fullFnName);
            }

            $values = null;
            if ($pluginId === "p") {
                if (str_contains($dSplit, "(")) {
                    $values = self::get_params_object(self::get_params_list($dSplit, ""));
                }
            } else {
                $values = self::get_params_object(self::get_params_list($dSplit, ""));
            }

            $transformation = [
                "plugin" => $pluginId,
                "name" => $operationName
            ];

            if (!empty($values)) {
                $transformation["values"] = $values;
            }
            return $transformation;
        }

        public static function get_transformation_details_from_pattern($pattern, string $url, bool $flatten = false)
        {
            if ($pattern == "original") {
                return [];
            }

            $dSplit = explode(self::OPERATION_SEPARATOR, $pattern);

            $map_func = function (string $x) {
                $result = self::get_operation_details_from_operation($x);
                $name = $result["name"];
                $plugin = $result["plugin"];
                $values = array_key_exists("values", $result) ? $result["values"] : null;

                if (!empty($values) && count($values) > 0) {
                    $map_key_value = function ($x) use ($values) {
                        return [
                            "key" => $x,
                            "value" => $values[$x]
                        ];
                    };

                    $values = array_map($map_key_value, array_keys($values));

                    return [
                        "plugin" => $plugin,
                        "name" => $name,
                        "values" => $values
                    ];
                }
                return [
                    "plugin" => $plugin,
                    "name" => $name
                ];
            };

            $opts = array_map($map_func, $dSplit);

            if ($flatten) {
                $opts = array_merge($opts);
            }
            return $opts;
        }

        private static function get_obj_from_url(string $url, bool $flatten = false)
        {
            $parts = self::get_parts_from_url($url);
            try {
                $parts["transformations"] = !empty($parts["pattern"]) ? self::get_transformation_details_from_pattern(
                    $parts["pattern"],
                    $url,
                    $flatten
                ) : [];
            } catch (Exception $e) {
                throw new Exceptions\PixelbinInvalidUrlError("Error Processing url. Please check the url is correct, $e");
            }
            return $parts;
        }

        private static function get_url_from_obj(array $obj): string
        {
            if (!array_key_exists("baseUrl", $obj)) {
                $obj["baseUrl"] = self::BASE_URL;
            }

            if (!array_key_exists("cloudName", $obj)) {
                throw new Exceptions\PixelbinIllegalArgumentError("key cloudName should be defined");
            }
            if (!array_key_exists("filePath", $obj)) {
                throw new Exceptions\PixelbinIllegalArgumentError("key filePath should be defined");
            }

            $pattern_value = self::get_pattern_from_transformations($obj["transformations"]);
            $obj["pattern"] = $pattern_value !== null ? $pattern_value : "original";
            if (!array_key_exists("version", $obj) || !preg_match(self::VERSION2_REGEX, $obj["version"])) {
                $obj["version"] = "v2";
            }
            if (!array_key_exists("zone", $obj) || $obj["zone"] === null || !preg_match(self::ZONE_SLUG, $obj["zone"])) {
                $obj["zone"] = "";
            }

            $urlKeySorted = ["baseUrl", "version", "cloudName", "zone", "pattern", "filePath"];
            $urlArr = [];
            foreach ($urlKeySorted as $key) {
                if (array_key_exists($key, $obj) && !empty($obj[$key])) {
                    $urlArr[] = $obj[$key];
                }
            }

            $queryArr = [];
            if (array_key_exists("options", $obj) && count($obj["options"]) > 0) {
                [$dpr, $f_auto] = array_values($obj["options"]);
                if ($dpr) {
                    self::validate_dpr($dpr);
                    $queryArr[] = "dpr=" . number_format((float)$dpr, 1);
                }
                if ($f_auto) {
                    self::validate_f_auto($f_auto);
                    $queryArr[] = "f_auto=" . ucfirst(json_encode($f_auto));
                }
            }

            $urlStr = implode("/", $urlArr);
            if (count($queryArr) > 0) {
                $urlStr .= "?" . implode("&", $queryArr);
            }
            // $urlStr = preg_replace('/\xc2\xa0/', ' ', $urlStr);
            return $urlStr;
        }


        public static function get_pattern_from_transformations($transformationList): ?string
        {
            if (count($transformationList) === 0) {
                return null;
            }

            $mapfunc = function ($o) {
                if (array_key_exists("name", $o)) {
                    $o["values"] = array_key_exists("values", $o) ? $o["values"] : [];
                    $paramlist = [];
                    foreach ($o["values"] as $items) {
                        if (!array_key_exists("key", $items) || !$items["key"]) {
                            throw new Exceptions\PixelbinIllegalArgumentError("key not specified in '{$o['name']}'");
                        }
                        if (!array_key_exists("value", $items) || !$items["value"]) {
                            throw new Exceptions\PixelbinIllegalArgumentError("value not specified for '{$items['key']}' in '{$o['name']}'");
                        }
                        $paramlist[] = $items["key"] . ":" . $items["value"];
                    }
                    $paramstr = implode(self::PARAMETER_SEPARATOR, $paramlist);

                    if ($o["plugin"] === "p") {
                        return !empty($paramstr) ? "p:" . $o["name"] . "(" . $paramstr . ")" : "p:" . $o["name"];
                    }
                    return $o["plugin"] . "." . $o["name"] . "(" . $paramstr . ")";
                }
                //     return f"p:{o['name']}({paramstr})" if paramstr else f"p:{o['name']}"
                // return f"{o['plugin']}.{o['name']}({paramstr})"
                return null;
            };

            $transformationList = array_map($mapfunc, $transformationList);
            $transformationList = array_filter($transformationList, fn ($ele) => $ele !== null);
            return implode(self::OPERATION_SEPARATOR, $transformationList);
        }


        public static function validate_dpr(float $dpr)
        {
            if ($dpr < 0.1 || $dpr > 5.0) {
                throw new Exceptions\PixelbinIllegalQueryParameterError("DPR value should be numeric and should be between 0.1 and 5.0");
            }
        }

        public static function validate_f_auto($f_auto)
        {
            if (!is_bool($f_auto)) {
                throw new Exceptions\PixelbinIllegalQueryParameterError("F_auto valie should be boolean");
            }
        }

        public static function process_query_params(array $urlParts): array
        {
            $queryParams = explode("&", !empty($urlParts["search"]) ? $urlParts["search"] : "");
            $queryObj = array();

            foreach ($queryParams as $params) {
                $queryElements = explode("=", $params);

                if (in_array($queryElements[0], self::ALLOWED_OPTIONAL_PARAMS, true)) {
                    if ($queryElements[0] === "dpr") {
                        $queryObj["dpr"] = (float) $queryElements[1];
                        self::validate_dpr($queryObj["dpr"]);
                    } else {
                        $queryObj["f_auto"] = (bool) $queryElements[1];
                        self::validate_dpr($queryObj["f_auto"]);
                    }
                }
            }

            return $queryObj;
        }
    }
}
