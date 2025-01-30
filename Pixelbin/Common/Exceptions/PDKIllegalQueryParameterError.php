<?php

namespace Pixelbin\Common\Exceptions {

    use Exception;

    /**
     * Pixelbin Illegal Query Parameter Exception.
     */
    class PDKIllegalQueryParameterError extends Exception
    {
        public function __construct($message = "")
        {
            parent::__construct($message);
        }
    }
}
