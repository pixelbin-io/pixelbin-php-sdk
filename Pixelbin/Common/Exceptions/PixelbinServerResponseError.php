<?php

namespace Pixelbin\Common\Exceptions {

    use Exception;

    /**
     * Pixelbin Server Response Exception.
     */
    class PixelbinServerResponseError extends Exception
    {
        public function __construct($message = "")
        {
            parent::__construct($message);
        }
    }
}
