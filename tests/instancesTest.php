<?php
/**
 * SimpleTypes
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   SimpleTypes
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\SimpleTypes;

/**
 * Class InstancesTest
 * @package JBZoo\SimpleTypes
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

            $className = '\\JBZoo\\SimpleTypes\\' . ucfirst(str_replace('.php', '', $file));

            $obj = new $className('', $config);
            $this->assertInstanceOf('\\JBZoo\\SimpleTypes\\Type', $obj);

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

            $className = '\\JBZoo\\SimpleTypes\\Config' . ucfirst(str_replace('.php', '', $file));

            $obj = new $className();
            $this->assertInstanceOf('\\JBZoo\\SimpleTypes\\Config', $obj);

            $count++;
        }

        $this->assertEquals($typeCount, $count, 'Some configs or types are not found');
    }
}
