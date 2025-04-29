<?php

namespace Pixelbin\Utils\Url {
    class UrlObj
    {
        public bool $isCustomDomain;
        public string $protocol;
        public string $host;
        public string $search;
        public bool $worker;
        public string $workerPath;
        public string $version;
        public string $baseUrl;
        public string $filePath;
        public string $pattern;
        public string $cloudName;
        public UrlObjOptions|null $options;
        public string $zone;
        public array|null $transformations;

        public function __construct(
            $protocol = "",
            $host = "",
            $search = "",
            $workerPath = "",
            $version = "",
            $baseUrl = "",
            $filePath = "",
            $pattern = "",
            $cloudName = "",
            $zone = "",
            $worker = false,
            $isCustomDomain = false,
            $options = null,
            $transformations = null
        ) {
            $this->protocol = $protocol;
            $this->host = $host;
            $this->search = $search;
            $this->worker = $worker;
            $this->workerPath = $workerPath;
            $this->version = $version;
            $this->baseUrl = $baseUrl;
            $this->filePath = $filePath;
            $this->pattern = $pattern;
            $this->cloudName = $cloudName;
            $this->options = $options;
            $this->zone = $zone;
            $this->transformations = $transformations;
            $this->isCustomDomain = $isCustomDomain;
        }

        public function equals($other)
        {
            $optionsValid = false;
            $transformationsValid = false;

            if ($this->options !== null && $other->options !== null) {
                $optionsValid = (string) $this->options === (string) $other->options;
            } elseif ($this->options !== null ^ $other->options !== null) {
                $optionsValid = false;
            }

            if ($this->transformations !== null && $other->transformations !== null) {
                $transformationsValid = UrlTransformation::get_string($this->transformations) === UrlTransformation::get_string($other->transformations);
            } elseif ($this->transformations !== null ^ $other->transformations !== null) {
                $transformationsValid = false;
            }

            return $this->protocol === $other->protocol &&
                $this->host === $other->host &&
                $this->search === $other->search &&
                $this->worker === $other->worker &&
                $this->workerPath === $other->workerPath &&
                $this->version === $other->version &&
                $this->baseUrl === $other->baseUrl &&
                $this->filePath === $other->filePath &&
                $this->pattern === $other->pattern &&
                $this->cloudName === $other->cloudName &&
                $this->zone === $other->zone &&
                $this->isCustomDomain === $other->isCustomDomain &&
                $optionsValid && $transformationsValid;
        }

        public function __toString()
        {
            return json_encode($this);
        }
    }
}
