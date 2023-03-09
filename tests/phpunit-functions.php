<?php

/**
 * JBZoo Toolbox - SimpleTypes.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/SimpleTypes
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use JBZoo\SimpleTypes\Config\AbstractConfig;
use JBZoo\SimpleTypes\Config\Money as ConfigMoney;
use JBZoo\SimpleTypes\Type\Money;

function val(mixed $arg = null): Money
{
    AbstractConfig::registerDefault('money', new ConfigMoney());

    return new Money($arg);
}

function batchEqualDumps(array $testList): void
{
    foreach ($testList as $test) {
        $result = $test[0] ?? null;
        $arg    = $test[1] ?? null;

        is(val($arg)->dump(false), $result);
    }
}
