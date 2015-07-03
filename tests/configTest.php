<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/../src/autoload.php');
require_once realpath(__DIR__ . '/_configs.php');

/**
 * Class configTest
 * @package SmetDenis\SimpleTypes
 */
class configTest extends PHPUnit
{

    function testEmptyValid()
    {
        $money = new Money('1 i', new ConfigTestEmpty());
        $this->assertEquals('1 i', $money->dump(false));
    }

    function testRegisterDefault()
    {
        Config::registerDefault('weight', new ConfigTestWeight());
        Config::registerDefault('info', new ConfigTestInfo());

        $this->_batchEquals(array(
            // weight
            ['1 gram', (new Weight('1gram'))->dump(false)],
            ['1000 gram', (new Weight('1kg'))->convert('gram')->dump(false)],

            // info
            ['1 byte', (new Info('1'))->dump(false)],
            ['1 kb', (new Info('1024byte'))->convert('kb')->dump(false)],
        ));
    }


}
