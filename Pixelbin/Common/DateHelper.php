<?php

namespace Pixelbin\Common {
    class DateHelper
    {
        public static function get_ist_now()
        {
            // Set the timezone
            $timezone = timezone_open(TIMEZONE);

            // Get the current datetime in Indian Standard Time
            $datetime = date_create("now", $timezone);

            return $datetime;
        }
    }
}
