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

use JBZoo\SimpleTypes\Formatter;

/**
 * Class roundTest
 * @package JBZoo\SimpleTypes
 */
class RoundTest extends PHPUnit
{
    public function testNone()
    {
        $value = '123.456789';
        $rType = Formatter::ROUND_NONE;

        isBatch([
            ['123.456789 eur', val($value)->round(-3, $rType)->dump(false)],
            ['123.456789 eur', val($value)->round(-2, $rType)->dump(false)],
            ['123.456789 eur', val($value)->round(-1, $rType)->dump(false)],
            ['123.456789 eur', val($value)->round(0, $rType)->dump(false)],
            ['123.456789 eur', val($value)->round(1, $rType)->dump(false)],
            ['123.456789 eur', val($value)->round(2, $rType)->dump(false)],
            ['123.456789 eur', val($value)->round(3, $rType)->dump(false)],
        ]);
    }

    public function testClassic()
    {
        $value = '123.456789';
        $rType = Formatter::ROUND_CLASSIC;

        isBatch([
            ['0 eur', val($value)->round(-3, $rType)->dump(false)],
            ['100 eur', val($value)->round(-2, $rType)->dump(false)],
            ['120 eur', val($value)->round(-1, $rType)->dump(false)],
            ['123 eur', val($value)->round(0, $rType)->dump(false)],
            ['123.5 eur', val($value)->round(1, $rType)->dump(false)],
            ['123.46 eur', val($value)->round(2, $rType)->dump(false)],
            ['123.457 eur', val($value)->round(3, $rType)->dump(false)],
        ]);
    }

    public function testCeil()
    {
        $value = '111.111111';
        $rType = Formatter::ROUND_CEIL;

        isBatch([
            ['1000 eur', val($value)->round(-3, $rType)->dump(false)],
            ['200 eur', val($value)->round(-2, $rType)->dump(false)],
            ['120 eur', val($value)->round(-1, $rType)->dump(false)],
            ['112 eur', val($value)->round(0, $rType)->dump(false)],
            ['111.2 eur', val($value)->round(1, $rType)->dump(false)],
            ['111.12 eur', val($value)->round(2, $rType)->dump(false)],
            ['111.112 eur', val($value)->round(3, $rType)->dump(false)],
        ]);
    }

    public function testFloor()
    {
        $value = '999.999999';
        $rType = Formatter::ROUND_FLOOR;

        isBatch([
            ['0 eur', val($value)->round(-3, $rType)->dump(false)],
            ['900 eur', val($value)->round(-2, $rType)->dump(false)],
            ['990 eur', val($value)->round(-1, $rType)->dump(false)],
            ['999 eur', val($value)->round(0, $rType)->dump(false)],
            ['999.9 eur', val($value)->round(1, $rType)->dump(false)],
            ['999.99 eur', val($value)->round(2, $rType)->dump(false)],
            ['999.999 eur', val($value)->round(3, $rType)->dump(false)],
        ]);
    }

    public function testUndefinedMode()
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('123.456789')->round(-3, 'undefined');
    }
}
