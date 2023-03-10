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

use JBZoo\PHPUnit\Fixture\AbstractConfigTestWeight;
use JBZoo\SimpleTypes\Config\Money as ConfigMoney;
use JBZoo\SimpleTypes\Type\Money;
use JBZoo\SimpleTypes\Type\Weight;

final class CalcTest extends PHPUnit
{
    public function testAdd(): void
    {
        $val = val();

        batchEqualDumps([
            ['0 eur', $val->dump(false)],
            ['1 eur', $val->add('1')->dump(false)],
            ['0 eur', $val->add('-1')->dump(false)],
            ['10 eur', $val->add('10')->dump(false)],
            ['15 eur', $val->add('10 usd')->dump(false)],
            ['16 eur', $val->add(1)->dump(false)],
            ['15 eur', $val->add(-1)->dump(false)],
            ['15.56 eur', $val->add(0.56)->dump(false)],
            ['14.99 eur', $val->add(-0.57)->dump(false)],
        ]);
    }

    public function testSubtract(): void
    {
        $val = val();

        batchEqualDumps([
            ['0 eur', $val->dump(false)],
            ['-1 eur', $val->subtract('1')->dump(false)],
            ['0 eur', $val->subtract('-1')->dump(false)],
            ['-10 eur', $val->subtract('10')->dump(false)],
            ['-15 eur', $val->subtract('10 usd')->dump(false)],
            ['-16 eur', $val->subtract(1)->dump(false)],
            ['-15 eur', $val->subtract(-1)->dump(false)],
            ['-15.56 eur', $val->subtract(0.56)->dump(false)],
            ['-16.13 eur', $val->subtract(0.57)->dump(false)],
        ]);
    }

    public function testPercentAddAndSubtract(): void
    {
        $val = val(100);

        batchEqualDumps([
            ['100 eur', $val->dump(false)],

            // plus
            ['110 eur', $val->add('10%')->dump(false)],
            ['99 eur', $val->add('-10%')->dump(false)],
            ['108.9 eur', $val->add('+10%')->dump(false)],

            // minus
            ['-980.1 eur', $val->subtract('+1000%')->dump(false)],
            ['-2411.046 eur', $val->add('146%')->dump(false)],
        ]);
    }

    public function testAddAndSubtract(): void
    {
        $val = val('100 eur');

        $val->add(-10)
            ->add('50%')
            ->subtract(200)
            ->add('-50%')
            ->add(val('100 eur'))
            ->add(-10)
            ->subtract(-200);

        is($val->dump(false), '257.5 eur');

        is('3 %', val('1%')->add('2%')->dump(false));
    }

    public function testInvert(): void
    {
        $val = val('1 eur');

        batchEqualDumps([
            ['-1 eur', $val->invert()->dump(false)],
            ['1 eur', $val->invert()->dump(false)],
        ]);

        $val = val();

        batchEqualDumps([
            ['0 eur', $val->invert()->dump(false)],
            ['0 eur', $val->invert()->dump(false)],
        ]);
    }

    public function testPositiveAndNegative(): void
    {
        $val = val('1 eur');

        batchEqualDumps([
            ['1 eur', $val->positive()->dump(false)],
            ['-1 eur', $val->negative()->dump(false)],
            ['-1 eur', $val->negative()->dump(false)],
            ['1 eur', $val->positive()->dump(false)],
        ]);
    }

    public function testMultiply(): void
    {
        $val = val('1 eur');

        batchEqualDumps([
            ['1 eur', $val->multiply(1)->dump(false)],
            ['10 eur', $val->multiply(10)->dump(false)],
            ['-10 eur', $val->multiply(-1)->dump(false)],
            ['5.6 eur', $val->multiply(-.56)->dump(false)],
        ]);
    }

    public function testDivision(): void
    {
        $val = val('360 eur');

        batchEqualDumps([
            ['3000 eur', $val->division(.12)->dump(false)],
            ['100 eur', $val->division(30)->dump(false)],
        ]);
    }

    public function testPercent(): void
    {
        $discountSave = val('20 eur');
        $itemPrice    = val('100 eur');

        batchEqualDumps([
            ['20 %', $discountSave->percent($itemPrice)->dump(false)],
            ['40 %', val('10 eur')->percent('50 usd')->dump(false)],
            ['60 %', val('10 eur')->percent('50 usd', true)->dump(false)],
        ]);
    }

    public function testFunction(): void
    {
        $val = val('100 eur');

        $val
            ->customFunc(static function ($value): void {
                $value
                    ->multiply(6.5)
                    ->add('55%')
                    ->negative(true);
            })
            ->customFunc(static function ($value): void {
                $value
                    ->add([50, 'usd'])
                    ->convert('byr');
            });

        is('20650000 byr', $val->dump(false));
    }

    public function testChecks(): void
    {
        $val = val('-1 usd');

        is(true, $val->isNegative());
        is(false, $val->isPositive());
        is(false, $val->isEmpty());

        $val->set(1);
        is(false, $val->isNegative());
        is(true, $val->isPositive());
        is(false, $val->isEmpty());

        $val->setEmpty();
        is(false, $val->isNegative());
        is(false, $val->isPositive());
        is(true, $val->isEmpty());
    }

    public function testAbs(): void
    {
        is('1 eur', val('-1 eur')->abs()->dump(false));
        is('1 eur', val('1 eur')->abs()->dump(false));
        is('0 eur', val('0 eur')->abs()->dump(false));
    }

    public function testImpossibleAdd1(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);

        val('1 %')->add('1 usd');
    }

    public function testNoValidTypes(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);

        $money  = new Money('1 usd', new ConfigMoney());
        $weight = new Weight('1 kg', new AbstractConfigTestWeight());
        $money->add($weight);
    }
}
