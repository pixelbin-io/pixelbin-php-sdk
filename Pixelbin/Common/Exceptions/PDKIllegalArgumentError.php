<?php

namespace Pixelbin\Common\Exceptions {

    use Exception;

    /**
     * Pixelbin Illegal Argument Exception.
     */
    class PDKIllegalArgumentError extends Exception
    {
        public function __construct($message = "")
        {
            parent::__construct($message);
        }
    }
}
