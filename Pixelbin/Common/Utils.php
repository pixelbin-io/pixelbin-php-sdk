<?php

namespace Pixelbin\Common {

    use Pixelbin\Common\DateHelper;

    class Utils
    {
        /**
         * Creates query string
         *
         * @param array $params query params
         *
         * @return string
         */
        public static function create_query_string(array $params = []): string
        {
            $query_string = "";

            if (!empty($params)) {
                ksort($params);
                $final_params = [];

                foreach ($params as $key => $value) {
                    if (is_array($value)) {
                        asort($value);
                        $idx = 0;
                        foreach ($value as $k => $v) {
                            if (is_bool($v)) {
                                $final_params[] = "$key=" . json_encode($v);
                            } else {
                                $final_params[] = "$key=$v";
                            }
                            $idx++;
                        }
                    } elseif (is_bool($value)) {
                        $final_params[] = "$key=" . json_encode($value);
                    } else {
                        $final_params[] = "$key=$value";
                    }
                }

                $query_string = implode("&", $final_params);
                $query_string = urlencode($query_string);
            }

            return $query_string;
        }

        /**
         * Returns headers with signature
         * @param string $domain
         * @param string $method
         * @param string $url
         * @param string $query_string
         * @param array $headers
         * @param array|string|null $body
         * @param array $exclude_headers
         * @param bool $sign_query
         *
         * @return array|string
         */
        public static function add_signature_to_headers(string $domain, string $method, string $url, string $query_string, array $headers, array|string|null $body = "", array $exclude_headers = [], bool $sign_query = false): array|string
        {
            $query_string = urldecode($query_string);
            $ebg_date = DateHelper::get_ist_now()->format("Ymd\THis\Z");
            $headers_str = "";
            $host = str_replace(["https://", "http://"], "", $domain);
            $input_headers = $headers;
            $input_headers["host"] = $host;

            if (!$sign_query) {
                $input_headers["x-ebg-param"] = $ebg_date;
            } else {
                $query_string = $query_string . $query_string ? "&x-ebg-param=$ebg_date" : "?x-ebg-param=$ebg_date";
            }
            $excluded_headers = [];

            foreach ($exclude_headers as $header) {
                if (array_key_exists($header, $input_headers)) {
                    $excluded_headers[$header] = $input_headers[$header];
                    unset($input_headers[$header]);
                }
            }

            foreach ($input_headers as $key => $val) {
                $headers_str = $headers_str . "$key:$val\n";
            }

            $body_hex = hash("sha256", mb_convert_encoding("", "UTF-8"));

            if (!(is_null($body) || empty($body))) {
                $body_hex = hash("sha256", str_replace([", ", ": "], [",", ":"], json_encode($body, JSON_UNESCAPED_SLASHES)));
            }

            $header_keys = [];

            foreach ($input_headers as $key => $value) {
                if ($key == "host" || str_starts_with($key, "x-ebg-")) {
                    $header_keys[] = $key;
                }
            }

            $request_list = [
                strtoupper($method),
                $url,
                $query_string,
                $headers_str,
                implode(";", $header_keys),
                $body_hex
            ];

            $request_str = implode("\n", $request_list);
            $request_str = implode("\n", [$ebg_date, hash("sha256", mb_convert_encoding($request_str, "UTF-8"))]);
            $signature = "v1:" . hash_hmac("sha256", mb_convert_encoding($request_str, "UTF-8"), "1234567");

            if (!$sign_query) {
                $input_headers["x-ebg-signature"] = $signature;
            } else {
                $query_string = $query_string . "&x-ebg-signature=$signature";
            }

            foreach ($excluded_headers as $h_key => $h_value) {
                if (!empty($h_value)) {
                    $input_headers[$h_key] = $h_value;
                }
            }

            return !$sign_query ? $input_headers : $query_string;
        }

        public static function getSdkDetails(): array
        {
            return [
                "name" => "pixelbin/pixelbin",
                "version" => "1.0.1"
            ];
        }
    }
}
