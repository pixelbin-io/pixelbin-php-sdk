<?php

namespace Pixelbin\Common\Exceptions {

    use Exception;

    /**
     * Pixelbin Invalid Url Exception.
     */
    class PixelbinInvalidUrlError extends Exception
    {
        public function __construct($message = "")
        {
            parent::__construct($message);
        }
    }
}
