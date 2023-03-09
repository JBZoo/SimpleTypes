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

final class MagicTest extends PHPUnit
{
    public function testSerializing(): void
    {
        $valBefore = val('500 usd');
        $valString = \serialize($valBefore);
        $valAfter  = \unserialize($valString)->convert('eur');

        isSame('500 usd', $valBefore->dump(false));
        isSame('250 eur', $valAfter->dump(false));
        isSame(true, $valBefore->compare($valAfter));
    }

    public function testClone(): void
    {
        $original = val('500 usd');
        $clone    = clone $original;
        $clone->convert('eur');

        isSame('500 usd', $original->dump(false));
        isSame('250 eur', $clone->dump(false));
        isSame(true, $original->compare($clone));
    }

    public function testString(): void
    {
        $val = val('500 usd');

        isSame('$500.00', (string)$val);
        isSame('$500.00', '' . $val);
        isSame('$500.00', $val->__toString());
    }

    public function testGet(): void
    {
        $val = val('500 usd');

        isSame(500.0, $val->value);
        isSame('usd', $val->rule);
    }

    public function testSet(): void
    {
        $val = val('500 usd');

        $val->value = 100;
        is('100 eur', $val->dump(false));

        $val->value = '100usd';
        is('100 usd', $val->dump(false));

        $val->value = [100, 'rub'];
        is('100 rub', $val->dump(false));

        $val->rule = 'eur';
        is('2 eur', $val->dump(false));
    }

    public function testSetUndefined(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        $val = val('500 usd');

        $val->someUndefined = 100;
        is('500 usd', $val->dump(false));
    }

    public function testGetUndefined(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        $val = val('500 usd');
        $val->someUndefined;
    }

    public function testCall(): void
    {
        $val = val('1 eur');
        is($val->val('rub'), $val->value('rub'));
    }

    public function testCallAddAlias(): void
    {
        $val = val('1 eur')->plus('2 eur');
        is('3 eur', $val->dump(false));
    }

    public function testCallSubtractAlias(): void
    {
        $val = val('1 eur')->minus('2 eur');
        is('-1 eur', $val->dump(false));
    }

    public function testCallUndefined(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('1 eur')->undefined();
    }

    public function testInvoke(): void
    {
        $val = val('1 eur');

        isSame('2 usd', $val('usd')->dump(false));
        isSame('50 eur', $val('50')->dump(false));
        isSame('100 rub', $val('100 rub')->dump(false));
        isSame('100 uah', $val('100', 'uah')->dump(false));
    }

    public function testInvokeErrorNoArgs(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        $val = val('1 eur');
        $val();
    }

    public function testInvokeErrorTooManyArgs(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        $val = val('1 eur');
        $val(1, 2, 3);
    }

    public function testGetCloneAfterCalc(): void
    {
        $val1 = val(1);
        $val2 = $val1->add(2, true);

        is(true, $val1->getId() !== $val2->getId());

        $val1 = val(1);
        $val2 = $val1->add(2, false);

        is(true, $val1->getId() === $val2->getId());
    }
}
