<?php

namespace Pixelbin\Platform {
    /**
     * OAuth Client
     */
    class OAuthClient
    {
        // public WeakReference $_conf;
        public string $token;

        public function __construct(PixelbinConfig $config)
        {
            // $this->_conf = WeakReference::create($config);
            $this->token = $config->apiSecret;
        }

        public function get_access_token(): string
        {
            return $this->token;
        }
    }
}
