<?php

namespace Pixelbin\Platform\Enums {

    use Exception;

    enum AccessEnum: string
    {
        case PUBLIC_READ = "public-read";
        case PRIVATE = "private";

        public static function is_valid($value)
        {
            $validValues = [
                self::PUBLIC_READ->value,
                self::PRIVATE->value,
            ];

            if (in_array($value, $validValues, true)) {
                return null;
            }

            throw new Exception("Invalid AccessEnum type");
        }
    }
}
