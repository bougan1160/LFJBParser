<?php

/**
 * Created by PhpStorm.
 * User: yasuaki
 * Date: 4/5/15
 * Time: 11:35
 */
use Bougan1160\LFJBParser\LFJBParser;

class LFJBParserTest extends \PHPUnit_Framework_TestCase
{
    private $parser;
    private $originalJSONLD;
    private $parsedJSONLD;

    public function setUp()
    {
        $this->parser = new LFJBParser();
        $this->originalJSONLD = json_decode(file_get_contents("events-jsonld.jsonld"), true);
        $this->parsedJSONLD = json_decode(file_get_contents("parsed-events-jsonld.jsonld"), true);
    }

    public function testParseSinglePerformer()
    {
        //http://lfjb.biwako-hall.or.jp/events/487/jsonld/
        $performerData_Single = $this->originalJSONLD[0]['schema:performer'];
        $expected = $this->parsedJSONLD[0]['schema:performer'];
        $result = $this->parser->parsePerformer($performerData_Single);
        $this->assertEquals($expected, $result);
    }

    public function testParseManyPerformer()
    {
        //http://lfjb.biwako-hall.or.jp/events/491/jsonld/
        $performerData_Many = $this->originalJSONLD[14]['schema:performer'];
        $result = $this->parser->parsePerformer($performerData_Many);
        $expected = $this->parsedJSONLD[14]['schema:performer'];
        $this->assertEquals($expected, $result);

        //http://lfjb.biwako-hall.or.jp/events/503/jsonld/
        $performerData_Many = $this->originalJSONLD[4]['schema:performer'];
        $result = $this->parser->parsePerformer($performerData_Many);
        $expected = $this->parsedJSONLD[4]['schema:performer'];
        $this->assertEquals($expected, $result);
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage Unknown schema
     */
    public function testParseUnknownTypePerformer()
    {
        $performerData = $this->originalJSONLD[0];
        $this->parser->parsePerformer($performerData);
    }

    public function testParseDate()
    {
        $dateData = $this->originalJSONLD[0]['schema:startDate'];
        $result = $this->parser->parseDate($dateData);
        $expected = $this->parsedJSONLD[0]['schema:startDate'];
        $this->assertEquals($expected, $result);
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage Invalid date format
     */
    public function testParseInvalidFormatDate()
    {
        $result = $this->parser->parseDate("20120201 11:11");
        $expected = $this->parsedJSONLD[0]['schema:startDate'];
    }

    public function  testParse()
    {
        $eventData = $this->originalJSONLD[0];
        $expected = $this->parsedJSONLD[0];
        $result = $this->parser->parse($eventData, array('strongParse' => true));
        $this->assertEquals($expected, $result);
    }
}
 