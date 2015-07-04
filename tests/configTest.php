<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class configTest
 * @package SmetDenis\SimpleTypes
 */
class configTest extends PHPUnit
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

        $this->batchEquals(array(
            // weight
            ['1 gram', (new Weight('1gram'))->dump(false)],
            ['1000 gram', (new Weight('1kg'))->convert('gram')->dump(false)],

            // info
            ['1 byte', (new Info('1'))->dump(false)],
            ['1 kb', (new Info('1024byte'))->convert('kb')->dump(false)],
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
