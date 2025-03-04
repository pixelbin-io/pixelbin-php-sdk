<?php

namespace Pixelbin\Common\Exceptions {

    use Exception;

    /**
     * Pixelbin Illegal Argument Exception.
     *
     * @deprecated use PDKIllegalArgumentError instead
     */
    class PixelbinIllegalArgumentError extends Exception
    {
        public function __construct($message = "")
        {
            trigger_error(__METHOD__ . ' is deprecated', E_USER_DEPRECATED);

            parent::__construct($message);
        }
    }
}
