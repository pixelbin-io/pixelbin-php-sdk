<?php

namespace Pixelbin\Platform {
    use Pixelbin\Common\GuzzleHttpHelper;
    use Pixelbin\Common\Utils;
    use Pixelbin\Platform\PixelbinConfig;

    class APIClient
    {
        public static ?GuzzleHttpHelper $helper = null;

        public static function execute(
            PixelbinConfig $conf,
            string $method,
            string $url,
            array|null $query,
            array|null $body,
            string $contentType
        ): array {
            $token = base64_encode(mb_convert_encoding($conf->get_access_token(), "UTF-8"));
            $headers = ["Authorization" => "Bearer $token"];
            $sdk = Utils::getSdkDetails();
            $language = "php";
            $userAgent = $sdk["name"] . "/" . $sdk["version"] . " (" . $language . ")";
            if (!empty($conf->integrationPlatform)) {
                $userAgent = $conf->integrationPlatform . " " . $userAgent;
            }
            $headers["User-Agent"] = $userAgent;
            $data = $contentType === "multipart/form-data" ? null : $body;

            if (!empty($contentType) && $contentType !== "multipart/form-data" && !empty($body))
                $headers["Content-Type"] = $contentType;

            if (!empty($query) && strtoupper($method) === 'GET') {
                $get_params = [];

                foreach ($query as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $arr_k => $arr_v) {
                            $get_params[$k . "[" . $arr_k . "]"] = $arr_v;
                        }
                    } elseif (is_bool($v)) {
                        $get_params[$k] = json_encode($v);
                    } else {
                        $get_params[$k] = $v;
                    }
                }
                $query = $get_params;
            }

            //
            // Skipping signature check for URLS starting with `/service/platform/` i.e. platform APIs of all services.
            //
            // $query_string = Utils::create_query_string($query);
            // $headers_with_sign = Utils::add_signature_to_headers(
            //     $conf->domain,
            //     $method,
            //     $url,
            //     $query_string,
            //     $headers,
            //     $data,
            //     ["Authorization", "Content-Type", "User-Agent"]
            // );
            // $headers_with_sign["x-ebg-param"] = base64_encode(mb_convert_encoding($headers_with_sign["x-ebg-param"], "UTF-8"));

            $host = str_replace("http://", "", str_replace("https://", "", $conf->domain));
            $headers["host"] = $host;
            if (static::$helper === null)
                static::$helper = new GuzzleHttpHelper();
            return static::$helper->request($method, $conf->domain . $url, $query, $body, $headers);
        }
    }
}
