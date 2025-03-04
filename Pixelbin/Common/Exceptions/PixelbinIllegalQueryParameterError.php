<?php

namespace Pixelbin\Common\Exceptions {

    use Exception;

    /**
     * Pixelbin Illegal Query Parameter Exception.
     *
     * @deprecated use PDKIllegalQueryParameterError instead
     */
    class PixelbinIllegalQueryParameterError extends Exception
    {
        public function __construct($message = "")
        {
            trigger_error(__METHOD__ . ' is deprecated', E_USER_DEPRECATED);

            parent::__construct($message);
        }
    }
}
