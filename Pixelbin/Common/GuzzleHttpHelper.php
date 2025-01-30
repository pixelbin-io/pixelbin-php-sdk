<?php

namespace Pixelbin\Common {

    use Exception;
    use GuzzleHttp\Client;
    use GuzzleHttp\Cookie\CookieJar;
    use GuzzleHttp\Cookie\SetCookie;
    use GuzzleHttp\Exception\ClientException;
    use GuzzleHttp\RequestOptions;
    use Pixelbin\Common\DateHelper;

    /**
     * Http helper
     */
    class GuzzleHttpHelper
    {
        public function makeRequestAsync(string $method, string $url, ?array $params, string|array $data, array $headers, int $timeoutAllowed): array
        {
            $cookieJar = new CookieJar();
            $start_time = microtime(true);
            $client = new Client([
                'cookies' => true,
                'timeout' => $timeoutAllowed,
            ]);

            $requestOptions = [
                RequestOptions::HEADERS => $headers,
                RequestOptions::COOKIES => $cookieJar,
            ];

            if (!empty($params)) {
                $requestOptions[RequestOptions::QUERY] = $params;
            }

            if (!empty($data)) {
                if (is_array($data)) {
                    $requestOptions[RequestOptions::MULTIPART] = $data;
                } else {
                    $requestOptions[RequestOptions::BODY] = $data;
                }
            }

            $responseBody = [
                "url" => $url,
                "method" => $method,
                "params" => $params,
                "data" => $data,
                "external_call_request_time" => DateHelper::get_ist_now()->format('Y-m-d H:i:s'),
                "status_code" => null,
                "text" => "",
                "headers" => "",
                "cookies" => $cookieJar,
                "error_message" => ""
            ];

            try {
                $response = $client->requestAsync(strtoupper($method), $url, $requestOptions)->wait();
                $responseBody["status_code"] = $response->getStatusCode();
                $responseBody["headers"] = $response->getHeaders();
                $headerSetCookies = $response->getHeader('Set-Cookie');

                $cookies = [];
                foreach ($headerSetCookies as $key => $header) {
                    $cookie = SetCookie::fromString($header);
                    $cookie->setDomain('YOUR_DOMAIN');

                    $cookies[] = $cookie;
                }
                $responseBody["cookies"] = $cookies;

                $responseBody["text"] = $response->getBody()->getContents();
                $responseBody["content"] = json_decode($responseBody["text"], true, flags: JSON_UNESCAPED_SLASHES);
            } catch (ClientException $e) {
                $responseBody["status_code"] = $e->getResponse()->getStatusCode();
                $responseBody["error_message"] = $e->getResponse()->getBody()->getContents();
                $responseBody["error_message"] = print_r(json_decode($responseBody["error_message"], true, flags: JSON_UNESCAPED_SLASHES), true);
            } catch (Exception $e) {
                $responseBody["status_code"] = 500;
                $responseBody["error"] = $e;
            }

            return $responseBody;
        }
        /**
         * Call API using GuzzleHttp.
         * 
         * @param string $method Request method type
         * @param string $url URL to be hit
         * @param array|null $params Query parameters
         * @param array|null $data Request body data
         * @param array $headers Headers
         * @param int $timeoutAllowed Timeout for request in seconds
         * 
         * @return array Response data
         */
        public function request(
            string $method,
            string $url,
            ?array $params,
            ?array $data,
            array $headers,
            int $timeoutAllowed = HTTP_TIMEOUT
        ): array {
            if (!empty($data)) {
                if (array_key_exists("file", $data)) {
                    $formData = [];
                    foreach ($data as $key => $value) {
                        if ($value instanceof \BackedEnum) {
                            $formData[] = [
                                'name' => $key,
                                'contents' => $value->value
                            ];
                        } elseif (is_object($value) || is_bool($value)) {
                            $formData[] = [
                                'name' => $key,
                                'contents' => json_encode($value, JSON_UNESCAPED_SLASHES)
                            ];
                        } elseif (is_array($value) && array_is_list($value)) {
                            foreach ($value as $element) {
                                $formData[] = [
                                    'name' => $key,
                                    'contents' => $element
                                ];
                            }
                        } elseif (is_resource($value)) {
                            $metadata = stream_get_meta_data($value);
                            $formData[] = [
                                "name" => $key,
                                "contents" => $value,
                                "filename" => basename($metadata["uri"])
                            ];
                        } else {
                            $formData[] = [
                                "name" => $key,
                                "contents" => $value
                            ];
                        }
                    }
                    $data = $formData;
                } else {
                    $data = json_encode((object) $data, JSON_UNESCAPED_SLASHES);
                }
            }
            $response = $this->makeRequestAsync($method, $url, $params, $data, $headers, $timeoutAllowed);
            return $response;
        }
    }
}
