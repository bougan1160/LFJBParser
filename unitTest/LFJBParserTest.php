<?php

/**
 * Created by PhpStorm.
 * User: yasuaki
 * Date: 4/5/15
 * Time: 11:35
 */
require dirname(dirname(dirname(__FILE__))) . '/vendor/autoload.php';

class LFJBParserTest extends PHPUnit_Framework_TestCase
{

    public function tearUp()
    {
        $this->parser = new LFJBParser();
    }

    public function testParsePerformer()
    {
        $this->assertEquals(1, 1);
    }
}
 