<?php

namespace Pixelbin\Platform {
    require_once(__DIR__ . "/../autoload.php");

    use Pixelbin\Common\Exceptions;
    use WeakReference;

    /**
     * PixelbinConfig holds the configuration details
     */
    class PixelbinConfig
    {
        public string $domain;

        public string $apiSecret = "";
        private WeakReference $oauthClient;

        /**
         * Create an instance of PixelbinConfig
         * @param array $config
         */
        public function __construct(array $config)
        {
            $this->domain = array_key_exists("domain", $config) ? $config["domain"] : DEFAULT_DOMAIN;
            $this->apiSecret = array_key_exists("apiSecret", $config) ? $config["apiSecret"] : "";
            $this->oauthClient = WeakReference::create(new OAuthClient($this));
            $this->validate();
        }

        /**
         * Return the access token
         * @return string
         */
        public function get_access_token(): string
        {
            // $token = $this->oauthClient->get()->get_access_token();
            return $this->apiSecret;
        }

        /**
         * Validates apiSecret
         * @return void
         */
        private function validate(): void
        {
            if (empty($this->apiSecret)) {
                throw new Exceptions\PixelbinInvalidCredentialError("No API Secret Token Present");
            }

            if (strlen($this->apiSecret) < APPLICATION_MIN_TOKEN_LENGTH) {
                throw new Exceptions\PixelbinInvalidCredentialError("Invalid API Secret Token");
            }
        }
    }
}
