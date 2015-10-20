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

use JBZoo\SimpleTypes\Config;
use JBZoo\SimpleTypes\ConfigMoney;
use JBZoo\SimpleTypes\Money;

/**
 * @param null $arg
 * @return \JBZoo\SimpleTypes\Money
 */
function val($arg = null)
{
    Config::registerDefault('money', new ConfigMoney());

    return new Money($arg);
}

/**
 * @param array $testList
 */
function batchEqualDumps($testList)
{
    $testList = (array)$testList;

    foreach ($testList as $test) {
        $result = isset($test[0]) ? $test[0] : null;
        $arg    = isset($test[1]) ? $test[1] : null;
        is(val($arg)->dump(false), $result);
    }
}
