<?php

/**
 * Created by PhpStorm.
 * User: yasuaki
 * Date: 4/5/15
 * Time: 10:17
 */
class LFJBParser
{


    function __construct()
    {
    }

    public function parse($eventJSON, $options)
    {
        $defaultOptions = array();
    }

    public function parsePerformer($performerJSON, $options)
    {
        if (isset($performerJSON['@type']) && $performerJSON['@type'] == "schema:Person") {
            throw new Exception("Unknown schema");
        }

    }

    public function parseDate($dateJSON, $options)
    {

    }
}