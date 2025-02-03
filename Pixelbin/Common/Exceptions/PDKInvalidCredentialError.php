<?php

namespace Pixelbin\Common\Exceptions {

    use Exception;

    /**
     * Pixelbin Invalid credential exception.
     */
    class PDKInvalidCredentialError extends Exception
    {
        public function __construct($message = "Invalid Credentials")
        {
            parent::__construct($message);
        }
    }
}
