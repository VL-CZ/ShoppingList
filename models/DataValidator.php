<?php


class DataValidator
{
    /**
     * DataValidator constructor.
     */
    public function __construct()
    {
    }

    /**
     * check if the value is positive integer
     * @param $variable
     * @return bool
     */
    public function isPositiveInteger($variable)
    {
        return is_int($variable) && intval($variable) > 0;
    }
}