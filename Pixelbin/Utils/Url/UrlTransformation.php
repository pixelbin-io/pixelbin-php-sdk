<?php

namespace Pixelbin\Utils\Url {
    class UrlTransformation
    {
        public array|null $values;
        public string|null $plugin;
        public string|null $name;

        public function __construct($values = null, $plugin = null, $name = null)
        {
            $this->values = $values;
            $this->plugin = $plugin;
            $this->name = $name;
        }

        public function __toString()
        {
            return json_encode($this);
        }

        public static function get_string($transformations)
        {
            return implode('', array_map(function ($o) {
                return (string) $o;
            }, $transformations));
        }
    }
}
