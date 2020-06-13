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
use JBZoo\SimpleTypes\Type\Info;
use JBZoo\SimpleTypes\Type\Money;
use JBZoo\SimpleTypes\Type\Weight;

/**
 * Class configTest
 * @package JBZoo\SimpleTypes
 */
class ConfigTest extends PHPUnit
{
    public function testEmptyValid()
    {
        $money = new Money('1 i', new ConfigTestEmpty());
        is('1 i', $money->dump(false));
    }

    public function testRegisterDefault()
    {
        Config::registerDefault('weight', new ConfigTestWeight());
        Config::registerDefault('info', new ConfigTestInfo());

        // weight
        $weight1 = new Weight('1gram');
        $weight2 = new Weight('1kg');

        isBatch([

            ['1 gram', $weight1->dump(false)],
            ['1000 gram', $weight2->convert('gram')->dump(false)],
        ]);

        // info
        $info1 = new Info(1);
        $info2 = new Info('1024byte');

        isBatch([
            ['1 byte', $info1->dump(false)],
            ['1 kb', $info2->convert('kb')->dump(false)],
        ]);
    }

    public function testEmptyDefault()
    {
        is(null, Config::getDefault('undefined'));
    }

    public function testWrongDefault()
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        new Money(null, new ConfigTestWrong());
    }
}
