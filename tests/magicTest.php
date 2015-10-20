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

namespace JBZoo\SimpleTypes;

/**
 * Class magicTest
 * @package JBZoo\SimpleTypes
 */
class MagicTest extends PHPUnit
{

    public function testSerializing()
    {
        $valBefore = $this->val('500 usd');
        $valString = serialize($valBefore);
        $valAfter  = unserialize($valString)->convert('eur');

        $this->batchEquals(array(
            array('500 usd', $valBefore->dump(false)),
            array('250 eur', $valAfter->dump(false)),
            array(true, $valBefore->compare($valAfter)),
        ));
    }

    public function testClone()
    {
        $original = $this->val('500 usd');
        $clone    = clone $original;
        $clone->convert('eur');

        $this->batchEquals(array(
            array('500 usd', $original->dump(false)),
            array('250 eur', $clone->dump(false)),
            array(true, $original->compare($clone)),
        ));
    }

    public function testString()
    {
        $val = $this->val('500 usd');

        $this->batchEquals(array(
            array('$500.00', (string)$val),
            array('$500.00', '' . $val),
            array('$500.00', $val->__toString()),
        ));
    }

    public function testGet()
    {
        $val = $this->val('500 usd');

        $this->batchEquals(array(
            array(500.0, $val->value),
            array('usd', $val->rule),
        ));
    }

    public function testSet()
    {
        $val = $this->val('500 usd');

        $val->VALUE = 100;
        $this->assertEquals('100 eur', $val->dump(false));

        $val->value = '100usd';
        $this->assertEquals('100 usd', $val->dump(false));

        $val->value = array(100, 'rub');
        $this->assertEquals('100 rub', $val->dump(false));

        $val->rule = 'eur';
        $this->assertEquals('2 eur', $val->dump(false));
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testSetUndefined()
    {
        $val = $this->val('500 usd');

        $val->someUndefined = 100;
        $this->assertEquals('500 usd', $val->dump(false));
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testGetUndefined()
    {
        $val  = $this->val('500 usd');
        $prop = $val->someUndefined;
    }

    public function testCall()
    {
        $val = $this->val('1 eur');
        $this->assertEquals($val->val('rub'), $val->value('rub'));
    }

    public function testCallAddAlias()
    {
        $val = $this->val('1 eur')->plus('2 eur');
        $this->assertEquals('3 eur', $val->dump(false));
    }

    public function testCallSubtractAlias()
    {
        $val = $this->val('1 eur')->minus('2 eur');
        $this->assertEquals('-1 eur', $val->dump(false));
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testCallUndefined()
    {
        $this->val('1 eur')->undefined();
    }

    public function testInvoke()
    {
        $val = $this->val('1 eur');

        $this->batchEquals(array(
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
        $val = $this->val('1 eur');
        $val();
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testInvokeErrorTooManyArgs()
    {
        $val = $this->val('1 eur');
        $val(1, 2, 3);
    }

    public function testGetCloneAfterCalc()
    {
        $val1 = $this->val(1);
        $val2 = $val1->add(2, true);

        $this->assertEquals(true, $val1->getId() !== $val2->getId());

        $val1 = $this->val(1);
        $val2 = $val1->add(2, false);

        $this->assertEquals(true, $val1->getId() === $val2->getId());
    }
}
