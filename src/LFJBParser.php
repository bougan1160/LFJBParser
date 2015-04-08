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
        $this->defaultOptions = array(
            "strongParse" => false
        );
    }

    public function parse($eventJSON, $options)
    {
    }

    /**
     * @param $performerJSON
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public function parsePerformer($performerJSON, $options = array())
    {
        $_options = array_merge($this->defaultOptions, $options);
        if (!isset($performerJSON['@type']) || $performerJSON['@type'] != "schema:Person") {
            throw new \Exception("Unknown schema");
        }
        $names = $performerJSON['schema:name'];
        $performers = array();
        if (is_array($names)) {
            foreach ($names as $name) {
                $performers = array_merge($performers, $this->explodePerformerName($name, $_options['strongParse']));
            }
        } else {
            $performers = $this->explodePerformerName($names);
        }
        if (count($performers) >= 2)
            $performerJSON = $this->createPersonSchema($performers);
        return $performerJSON;
    }

    public function parseDate($dateJSON, $options = array())
    {
        $dateStr = mb_convert_kana($dateJSON, "as");
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i', $dateStr, new \DateTimeZone('Asia/Tokyo'));
        if ($dateTime == FALSE) {
            throw new \Exception('Invalid date format');
        }
        return $dateTime->format(\DateTime::ISO8601);
    }

    private function createPersonSchema($personNames)
    {
        $persons = array();
        foreach ($personNames as $personName) {
            if (strpos($personName, "（") === 0) {
                $persons[count($persons) - 1]['schema:name'] = $persons[count($persons) - 1]['schema:name'] . $personName;
            } else {
                $persons[] = array(
                    "@type" => "schema:Person",
                    "schema:name" => $personName
                );
            }
        }
        return $persons;
    }

    private function explodePerformerName($name, $strong = false)
    {
        // "（"ではじまりかつ "／" がある場合は "（）"を外す
        // TODO 誤変換の可能性があるため要検証
        if ($strong && strpos($name, "（") === 0 && strpos($name, "／") !== FALSE) {
            $name = preg_replace("/\A（|）\z/u", "", $name);
        }
        $name = preg_replace("/([（|(][^）|^)]+、.+[）|)])|([（|(][^）|^)]+／.+[）|)])|(、)|(／)/u", "$1$2,", $name);
        $name = preg_replace("/([)|）]) |([)|）])/u", "$1$2,", $name);
        return array_filter(explode(",", $name), "strlen");
    }
}