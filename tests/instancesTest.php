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
 * Class InstancesTest
 * @package SmetDenis\SimpleTypes
 */
class InstancesTest extends PHPUnit
{

    public function testCreateTypes()
    {
        $config = new ConfigTestEmpty();
        $files  = scandir(realpath(__DIR__ . '/../src/type'));

        $count = 0;

        foreach ($files as $file) {
            if ($file == '.' || $file == '..' || strpos($file, '.php') === false) {
                continue;
            }

            $className = '\\SmetDenis\\SimpleTypes\\' . ucfirst(str_replace('.php', '', $file));

            $obj = new $className('', $config);
            $this->assertInstanceOf('\\SmetDenis\\SimpleTypes\\Type', $obj);

            $count++;
        }

        return $count;
    }

    /**
     * @depends testCreateTypes
     */
    public function testCreateConfigs($typeCount)
    {
        $files = scandir(realpath(__DIR__ . '/../src/config'));

        $count = 0;

        foreach ($files as $file) {
            if ($file == '.' || $file == '..' || strpos($file, '.php') === false) {
                continue;
            }

            $className = '\\SmetDenis\\SimpleTypes\\Config' . ucfirst(str_replace('.php', '', $file));

            $obj = new $className();
            $this->assertInstanceOf('\\SmetDenis\\SimpleTypes\\Config', $obj);

            $count++;
        }

        //$this->cliMessage($typeCount . '|' . $count);
    }
}
