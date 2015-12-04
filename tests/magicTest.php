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
        $valAfter  = unserialize($valString)->convert('eur');

        isBatch(array(
            array('500 usd', $valBefore->dump(false)),
            array('250 eur', $valAfter->dump(false)),
            array(true, $valBefore->compare($valAfter)),
        ));
    }

    public function testClone()
    {
        $original = val('500 usd');
        $clone    = clone $original;
        $clone->convert('eur');

        isBatch(array(
            array('500 usd', $original->dump(false)),
            array('250 eur', $clone->dump(false)),
            array(true, $original->compare($clone)),
        ));
    }

    public function testString()
    {
        $val = val('500 usd');

        isBatch(array(
            array('$500.00', (string)$val),
            array('$500.00', '' . $val),
            array('$500.00', $val->__toString()),
        ));
    }

    public function testGet()
    {
        $val = val('500 usd');

        isBatch(array(
            array(500.0, $val->value),
            array('usd', $val->rule),
        ));
    }

    public function testSet()
    {
        $val = val('500 usd');

        $val->VALUE = 100;
        is('100 eur', $val->dump(false));

        $val->value = '100usd';
        is('100 usd', $val->dump(false));

        $val->value = array(100, 'rub');
        is('100 rub', $val->dump(false));

        $val->rule = 'eur';
        is('2 eur', $val->dump(false));
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testSetUndefined()
    {
        $val = val('500 usd');

        $val->someUndefined = 100;
        is('500 usd', $val->dump(false));
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testGetUndefined()
    {
        $val  = val('500 usd');
        $prop = $val->someUndefined;
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

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testCallUndefined()
    {
        val('1 eur')->undefined();
    }

    public function testInvoke()
    {
        $val = val('1 eur');

        isBatch(array(
            array('2 usd', $val('usd')->dump(false)),
            array('50 eur', $val('50')->dump(false)),
            array('100 rub', $val('100 rub')->dump(false)),
            array('100 uah', $val('100', 'uah')->dump(false)),
        ));
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testInvokeErrorNoArgs()
    {
        $val = val('1 eur');
        $val();
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testInvokeErrorTooManyArgs()
    {
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
