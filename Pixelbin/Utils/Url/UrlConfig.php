<?php

namespace Pixelbin\Utils\Url {
    class UrlConfig
    {
        public string|null $parameterSeparator;
        public string|null $operationSeparator;
        public bool|null $isCustomDomain;

        public function __construct($isCustomDomain = null, $parameterSeparator = null, $operationSeparator = null)
        {
            $this->isCustomDomain = $isCustomDomain;
            $this->parameterSeparator = $parameterSeparator;
            $this->operationSeparator = $operationSeparator;
        }
    }
}
