<?php
/**
 * JBZoo SimpleTypes
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

        isBatch(array(

            array('1 gram', $weight1->dump(false)),
            array('1000 gram', $weight2->convert('gram')->dump(false)),
        ));

        // info
        $info1 = new Info(1);
        $info2 = new Info('1024byte');

        isBatch(array(
            array('1 byte', $info1->dump(false)),
            array('1 kb', $info2->convert('kb')->dump(false)),
        ));
    }

    public function testEmptyDefault()
    {
        is(null, Config::getDefault('undefined'));
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testWrongDefault()
    {
        new Money(null, new ConfigTestWrong());
    }
}
