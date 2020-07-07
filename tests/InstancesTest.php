<?php

/**
 * JBZoo Toolbox - SimpleTypes
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    SimpleTypes
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/SimpleTypes
 */

namespace JBZoo\PHPUnit;

use JBZoo\SimpleTypes\Config\Config;
use JBZoo\SimpleTypes\Type\AbstractType;

/**
 * Class InstancesTest
 * @package JBZoo\SimpleTypes
 */
class InstancesTest extends PHPUnit
{
    public function testCreateTypes()
    {
        $config = new ConfigTestEmpty();
        $files = scandir(realpath(__DIR__ . '/../src/Type'));

        $count = 0;

        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || strpos($file, '.php') === false || $file === 'AbstractType.php') {
                continue;
            }

            $className = '\\JBZoo\\SimpleTypes\\Type\\' . ucfirst(str_replace('.php', '', $file));

            $obj = new $className('', $config);

            isClass(AbstractType::class, $obj);

            $count++;
        }

        return $count;
    }

    /**
     * @depends testCreateTypes
     */
    public function testCreateConfigs($typeCount)
    {
        $files = scandir(realpath(__DIR__ . '/../src/Config'));

        $count = 0;

        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || strpos($file,
                    '.php') === false || strtolower($file) === 'config.php') {
                continue;
            }

            $className = '\\JBZoo\\SimpleTypes\\Config\\' . ucfirst(str_replace('.php', '', $file));

            $obj = new $className();
            isClass(Config::class, $obj);

            $count++;
        }

        is($typeCount, $count, 'Some configs or types are not found');
    }
}
