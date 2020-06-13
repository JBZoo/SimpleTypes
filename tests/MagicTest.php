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

namespace JBZoo\PHPUnit;

/**
 * Class magicTest
 * @package JBZoo\SimpleTypes
 */
class MagicTest extends PHPUnit
{
    public function testSerializing()
    {
        $valBefore = val('500 usd');
        $valString = serialize($valBefore);
        $valAfter = unserialize($valString)->convert('eur');

        isBatch([
            ['500 usd', $valBefore->dump(false)],
            ['250 eur', $valAfter->dump(false)],
            [true, $valBefore->compare($valAfter)],
        ]);
    }

    public function testClone()
    {
        $original = val('500 usd');
        $clone = clone $original;
        $clone->convert('eur');

        isBatch([
            ['500 usd', $original->dump(false)],
            ['250 eur', $clone->dump(false)],
            [true, $original->compare($clone)],
        ]);
    }

    public function testString()
    {
        $val = val('500 usd');

        isBatch([
            ['$500.00', (string)$val],
            ['$500.00', '' . $val],
            ['$500.00', $val->__toString()],
        ]);
    }

    public function testGet()
    {
        $val = val('500 usd');

        isBatch([
            [500.0, $val->value],
            ['usd', $val->rule],
        ]);
    }

    public function testSet()
    {
        $val = val('500 usd');

        $val->VALUE = 100;
        is('100 eur', $val->dump(false));

        $val->value = '100usd';
        is('100 usd', $val->dump(false));

        $val->value = [100, 'rub'];
        is('100 rub', $val->dump(false));

        $val->rule = 'eur';
        is('2 eur', $val->dump(false));
    }

    public function testSetUndefined()
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        $val = val('500 usd');

        $val->someUndefined = 100;
        is('500 usd', $val->dump(false));
    }

    public function testGetUndefined()
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        $val = val('500 usd');
        $val->someUndefined;
    }

    public function testCall()
    {
        $val = val('1 eur');
        is($val->val('rub'), $val->value('rub'));
    }

    public function testCallAddAlias()
    {
        $val = val('1 eur')->plus('2 eur');
        is('3 eur', $val->dump(false));
    }

    public function testCallSubtractAlias()
    {
        $val = val('1 eur')->minus('2 eur');
        is('-1 eur', $val->dump(false));
    }

    public function testCallUndefined()
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('1 eur')->undefined();
    }

    public function testInvoke()
    {
        $val = val('1 eur');

        isBatch([
            ['2 usd', $val('usd')->dump(false)],
            ['50 eur', $val('50')->dump(false)],
            ['100 rub', $val('100 rub')->dump(false)],
            ['100 uah', $val('100', 'uah')->dump(false)],
        ]);
    }

    public function testInvokeErrorNoArgs()
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        $val = val('1 eur');
        $val();
    }

    public function testInvokeErrorTooManyArgs()
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        $val = val('1 eur');
        $val(1, 2, 3);
    }

    public function testGetCloneAfterCalc()
    {
        $val1 = val(1);
        $val2 = $val1->add(2, true);

        is(true, $val1->getId() !== $val2->getId());

        $val1 = val(1);
        $val2 = $val1->add(2, false);

        is(true, $val1->getId() === $val2->getId());
    }
}
