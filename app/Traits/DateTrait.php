<?php

namespace App\Traits;

use DateTime;

/**
 * Date trait.
 *
 * Custom date trait to help with date related requirements.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
trait DateTrait
{
    /**
     * Create DateTime object given a format.
     *
     * @param string $dateTimeStr
     * @param string $format
     * @return string
     */
    public function createDateTime(string $dateTimeStr, string $format = 'Y-m-d H:i:s') : DateTime
    {
        return DateTime::createFromFormat($format, $dateTimeStr);
    }
}
