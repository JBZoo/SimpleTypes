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

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use JBZoo\SimpleTypes\Config\Config;
use JBZoo\SimpleTypes\Config\Money as ConfigMoney;
use JBZoo\SimpleTypes\Type\Money;

/**
 * @param null $arg
 * @return Money
 */
function val($arg = null): Money
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
        $result = $test[0] ?? null;
        $arg = $test[1] ?? null;

        is(val($arg)->dump(false), $result);
    }
}
