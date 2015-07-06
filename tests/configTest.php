<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class configTest
 * @package SmetDenis\SimpleTypes
 */
class ConfigTest extends PHPUnit
{

    public function testEmptyValid()
    {
        $money = new Money('1 i', new ConfigTestEmpty());
        $this->assertEquals('1 i', $money->dump(false));
    }

    public function testRegisterDefault()
    {
        Config::registerDefault('weight', new ConfigTestWeight());
        Config::registerDefault('info', new ConfigTestInfo());

        // weight
        $weight1 = new Weight('1gram');
        $weight2 = new Weight('1kg');

        $this->batchEquals(array(

            array('1 gram', $weight1->dump(false)),
            array('1000 gram', $weight2->convert('gram')->dump(false)),
        ));

        // info
        $info1 = new Info(1);
        $info2 = new Info('1024byte');

        $this->batchEquals(array(
            array('1 byte', $info1->dump(false)),
            array('1 kb', $info2->convert('kb')->dump(false)),
        ));
    }

    public function testEmptyDefault()
    {
        $this->assertEquals(null, Config::getDefault('undefined'));
    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    public function testWrongDefault()
    {
        new Money(null, new ConfigTestWrong());
    }
}
