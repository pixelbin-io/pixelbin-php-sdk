<?php

namespace Pixelbin\Common\Exceptions {

    use Exception;

    /**
     * Invalid credential exception.
     *
     * @deprecated use PDKInvalidCredentialError instead
     */
    class PixelbinInvalidCredentialError extends Exception
    {
        public function __construct($message = "Invalid Credentials")
        {
            trigger_error(__METHOD__ . ' is deprecated', E_USER_DEPRECATED);

            parent::__construct($message);
        }
    }
}
