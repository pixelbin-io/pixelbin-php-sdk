<?php

namespace Pixelbin\Utils\Url {

    class UrlObjOptions
    {
        public float|int|string|null $dpr;
        public bool|string|null $f_auto;

        public function __construct($dpr = null, $f_auto = null)
        {
            $this->dpr = $dpr;
            $this->f_auto = $f_auto;
        }

        public function __toString()
        {
            return json_encode($this);
        }
    }
}
