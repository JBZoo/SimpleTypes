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

final class ConverterTest extends PHPUnit
{
    public function testCheckExists(): void
    {
        batchEqualDumps([
            ['1 eur', val('1')->dump(false)], // eur is default
            ['1 eur', val('1 eur')->dump(false)],
            ['1 usd', val('1 usd')->dump(false)],
            ['1 rub', val('1 rub')->dump(false)],
            ['1 uah', val('1 uah')->dump(false)],
            ['1 byr', val('1 byr')->dump(false)],
        ]);
    }

    public function testSimple(): void
    {
        $val = val('1.25 usd');

        isSame(0.625, $val->val('eur'));
        isSame(1.25, $val->val('usd'));
        isSame('0.625 eur', $val->convert('eur')->dump(false));
        isSame('1.25 usd', $val->convert('usd')->dump(false));
        isSame('12500 byr', $val->convert('byr')->dump(false));
        isSame('31.25 rub', $val->convert('rub')->dump(false));
        isSame('0.625 eur', $val->convert('eur')->dump(false));
        isSame('1.25 usd', $val->convert('usd')->dump(false));
        isSame('1.25 usd', $val->convert('usd')->dump(false));
    }

    public function testUndefinedRuleSilent(): void
    {
        isSame('1.25 eur', val('1.25 qwe')->dump(false));
    }

    public function testUndefinedRuleFatal(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('1.25 usd')->convert('qwerty');
    }

    public function testEmptyRule(): void
    {
        is(true, val('1.25 usd')->convert('')->isRule('usd'));
        is(true, val('1.25 usd')->convert('', true)->isRule('usd'));
    }
}
