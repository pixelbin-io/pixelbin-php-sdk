<?php

namespace Pixelbin\Common\Exceptions {

    use Exception;

    /**
     * Pixelbin Server Response Exception.
     */
    class PDKServerResponseError extends Exception
    {
        public function __construct($message = "", $status_code = null)
        {
            parent::__construct($message, $status_code);
        }
    }
}
