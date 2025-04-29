<?php

namespace Pixelbin\Common\Exceptions {

    use Exception;

    /**
     * Pixelbin Invalid Url Exception.
     *
     * @deprecated use PDKInvalidUrlError instead
     */
    class PixelbinInvalidUrlError extends Exception
    {
        public function __construct($message = "")
        {
            trigger_error(__METHOD__ . ' is deprecated', E_USER_DEPRECATED);

            parent::__construct($message);
        }
    }
}
