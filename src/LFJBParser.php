<?php

/**
 * Created by PhpStorm.
 * User: yasuaki
 * Date: 4/5/15
 * Time: 10:17
 */

namespace Bougan1160\LFJBParser;
class LFJBParser
{

    function __construct()
    {
    }

    public function parse($eventJSON, $options)
    {
        $defaultOptions = array();
    }

    public function parsePerformer($performerJSON, $options = array())
    {
        if (!isset($performerJSON['@type']) || $performerJSON['@type'] != "schema:Person") {
            throw new \Exception("Unknown schema");
        }
        $names = $performerJSON['schema:name'];
        $performers = array();
        if (is_array($names)) {
            foreach ($names as $name) {
                $performers = array_merge($performers, $this->explodePerformerName($name));
            }
        } else {
            $performers = $this->explodePerformerName($names);
        }
        if (count($performers) >= 2)
            $performerJSON = $this->createPersonSchema($performers);
        return $performerJSON;
    }

    public function parseDate($dateJSON, $options)
    {

    }

    private function createPersonSchema($personNames)
    {
        $persons = array();
        foreach ($personNames as $personName) {
            $persons[] = array(
                "@type" => "schema:Person",
                "schema:name" => $personName
            );
        }
        return $persons;
    }

    private function explodePerformerName($name)
    {
        $name = preg_replace("/([（|(][^）|^)]+、.+[）|)])|([（|(][^）|^)]+／.+[）|)])|(、)|(／)/", "$1$2,", $name);
        $name = preg_replace("/） |）/", "）,", $name);
        return array_filter(explode(",", $name), "strlen");
    }
}