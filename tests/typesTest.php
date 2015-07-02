<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/../src/autoload.php');
require_once realpath(__DIR__ . '/_configs.php');

/**
 * Class typesTest
 * @package SmetDenis\SimpleTypes
 */
class typesTest extends PHPUnit
{

    function testCreate()
    {
        $config = new ConfigTestEmpty();

        $files = scandir(realpath(__DIR__ . '/../src/type'));

        foreach ($files as $file) {

            if ($file == '.' || $file == '..' || strpos($file, '.php') === false) {
                continue;
            }

            $className = '\\SmetDenis\\SimpleTypes\\' . ucfirst(str_replace('.php', '', $file));

            $obj = new $className('', $config);
            $this->assertInstanceOf('\\SmetDenis\\SimpleTypes\\Type', $obj);
        }
    }

}