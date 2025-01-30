<?php

namespace Pixelbin\Common\Exceptions {

    use Exception;

    /**
     * Invalid credential exception.
     */
    class PixelbinInvalidCredentialError extends Exception
    {
        public function __construct($message = "Invalid Credentials")
        {
            parent::__construct($message);
        }
    }
}
