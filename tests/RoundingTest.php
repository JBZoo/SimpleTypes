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

use JBZoo\SimpleTypes\Formatter;

final class RoundingTest extends PHPUnit
{
    public function testNone(): void
    {
        $value = '123.456789';
        $rType = Formatter::ROUND_NONE;

        isSame('123.456789 eur', val($value)->round(-3, $rType)->dump(false));
        isSame('123.456789 eur', val($value)->round(-2, $rType)->dump(false));
        isSame('123.456789 eur', val($value)->round(-1, $rType)->dump(false));
        isSame('123.456789 eur', val($value)->round(0, $rType)->dump(false));
        isSame('123.456789 eur', val($value)->round(1, $rType)->dump(false));
        isSame('123.456789 eur', val($value)->round(2, $rType)->dump(false));
        isSame('123.456789 eur', val($value)->round(3, $rType)->dump(false));
    }

    public function testClassic(): void
    {
        $value = '123.456789';
        $rType = Formatter::ROUND_CLASSIC;

        isSame('0 eur', val($value)->round(-3, $rType)->dump(false));
        isSame('100 eur', val($value)->round(-2, $rType)->dump(false));
        isSame('120 eur', val($value)->round(-1, $rType)->dump(false));
        isSame('123 eur', val($value)->round(0, $rType)->dump(false));
        isSame('123.5 eur', val($value)->round(1, $rType)->dump(false));
        isSame('123.46 eur', val($value)->round(2, $rType)->dump(false));
        isSame('123.457 eur', val($value)->round(3, $rType)->dump(false));
    }

    public function testCeil(): void
    {
        $value = '111.111111';
        $rType = Formatter::ROUND_CEIL;

        isSame('1000 eur', val($value)->round(-3, $rType)->dump(false));
        isSame('200 eur', val($value)->round(-2, $rType)->dump(false));
        isSame('120 eur', val($value)->round(-1, $rType)->dump(false));
        isSame('112 eur', val($value)->round(0, $rType)->dump(false));
        isSame('111.2 eur', val($value)->round(1, $rType)->dump(false));
        isSame('111.12 eur', val($value)->round(2, $rType)->dump(false));
        isSame('111.112 eur', val($value)->round(3, $rType)->dump(false));
    }

    public function testFloor(): void
    {
        $value = '999.999999';
        $rType = Formatter::ROUND_FLOOR;

        isSame('0 eur', val($value)->round(-3, $rType)->dump(false));
        isSame('900 eur', val($value)->round(-2, $rType)->dump(false));
        isSame('990 eur', val($value)->round(-1, $rType)->dump(false));
        isSame('999 eur', val($value)->round(0, $rType)->dump(false));
        isSame('999.9 eur', val($value)->round(1, $rType)->dump(false));
        isSame('999.99 eur', val($value)->round(2, $rType)->dump(false));
        isSame('999.999 eur', val($value)->round(3, $rType)->dump(false));
    }

    public function testUndefinedMode(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('123.456789')->round(-3, 'undefined');
    }
}
