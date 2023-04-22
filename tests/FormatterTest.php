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

final class FormatterTest extends PHPUnit
{
    public function testChange(): void
    {
        $val1 = val('50000.789 usd');

        isSame('$50 000.79', $val1->text());
        isSame('50 000.79$', $val1->changeRule('usd', ['format_positive' => '%v%s'])->text());
    }

    public function testAdd(): void
    {
        $val = val('50000 usd');

        $val->addRule('qwe', [
            'symbol'          => 'Q',
            'num_decimals'    => '0',
            'decimal_sep'     => ',',
            'thousands_sep'   => '.',
            'format_positive' => '%v %s',
            'format_negative' => '-%v %s',
            'rate'            => 0.15,
        ]);

        is('$50 000.00', $val->text());
        is('166.667 Q', $val->convert('qwe')->text());
    }

    public function testRemove(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        $val = val('50000 usd');
        $val->removeRule('rub');
        $val->convert('rub'); // Exception!
    }

    public function testSetEmptyRule(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('50000 usd')->addRule(' '); // Exception!
    }

    public function testAddExists(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('50000 usd')->addRule('rub');
    }

    public function testGetRule(): void
    {
        $rule = val('50000 rub')->getRuleData('usd');
        is('$', $rule['symbol']);
    }

    public function testGetRuleUndefined(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('50000 rub')->getRuleData('undefined');
    }

    public function testChangeUndefined(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('50000 usd')->changeRule('undefined', []); // Exception!
    }

    public function testDependenceChanges(): void
    {
        $val1 = val('usd')->changeRule('usd', ['format_positive' => '%v%s']);
        $val2 = val('usd');

        is('0.00$', $val1->text());
        is('$0.00', $val2->text());
    }

    public function testDependenceAdd(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('1 usd')->addRule('qwe', [])->convert('qwe');
        val('1 usd')->convert('qwe');
    }

    public function testDependenceRemove(): void
    {
        val('1 usd')->removeRule('rub');
        is('25 rub', val('1 usd')->convert('rub')->dump(false));
    }

    public function testIsRule(): void
    {
        $val = val('1 usd');

        is(true, $val->isRule('USD'));

        $val->convert('RUB');
        is(false, $val->isRule('USD'));
    }

    public function testGetRules(): void
    {
        $val = val();
        isKey('rub', $val->getRules());
        isKey('usd', $val->getRules());
        isKey('uah', $val->getRules());
        isKey('eur', $val->getRules());
        isKey('byr', $val->getRules());
    }
}
