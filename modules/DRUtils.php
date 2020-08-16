<?php

abstract class DRUtils
{
    /*
     * filter: Filter $datas with a filter valye 
     */
    public static function filter($data, $filter)
    {
        return array_values(array_filter($data, $filter));
    }

    public static function random(&$data) {

        // Get random item in the array
        $index = bga_rand(0, sizeof($data) - 1);
        $selectedData = $data[$index];

        // Remove the item from the array
        array_splice($data, $index, 1);

        // Return the selection
        return $selectedData;

    }

    public static function random_item($data, $count) {
        
        $return = array();

        for ($i = 0; $i < $count; $i++) {
            $return[] = self::random($data);
        }

        return $return;

    }

    /**
     * This will throw an exception if condition is false.
     * The message should be translated and shown to the user.
     *
     * @param $log string
     *            server side log message, no translation needed
     * @throws BgaUserException
     */
    static function userAssertTrue($message, $cond = false) {
        if ($cond)
            return;
        throw new BgaUserException($message);
    }

    /**
     * This will throw an exception if condition is false.
     * The message should not be translated.
     *
     * @param $log string
     *            server side log message, no translation needed
     * @throws BgaUserException
     */
    static function userSystemTrue($message, $cond = false) {
        if ($cond)
            return;
        throw new BgaVisibleSystemException($message);
    }

    /**
     * This will throw an exception if condition is false.
     * The message should be translated and shown to the user.
     *
     * @param $log string
     *            server side log message, no translation needed
     * @throws BgaUserException
     */
    static function userAssertFalse($message, $cond = true) {
        if (!$cond)
            return;
        throw new BgaUserException($message);
    }
}
