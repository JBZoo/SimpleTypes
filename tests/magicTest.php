<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class magicTest
 * @package SmetDenis\SimpleTypes
 */
class MagicTest extends PHPUnit
{

    public function testSerializing()
    {
        $valBefore = $this->val('500 usd');
        $valString = serialize($valBefore);
        $valAfter  = unserialize($valString)->convert('eur');

        $this->batchEquals(array(
            ['500 usd', $valBefore->dump(false)],
            ['250 eur', $valAfter->dump(false)],
            [true, $valBefore->compare($valAfter)],
        ));
    }

    public function testClone()
    {
        $original = $this->val('500 usd');
        $clone    = clone $original;
        $clone->convert('eur');

        $this->batchEquals(array(
            ['500 usd', $original->dump(false)],
            ['250 eur', $clone->dump(false)],
            [true, $original->compare($clone)],
        ));
    }

    public function testString()
    {
        $val = $this->val('500 usd');

        $this->batchEquals(array(
            ['$500.00', (string)$val],
            ['$500.00', '' . $val],
            ['$500.00', $val->__toString()],
        ));
    }

    public function testGet()
    {
        $val = $this->val('500 usd');

        $this->batchEquals(array(
            [500.0, $val->value],
            ['usd', $val->rule],
        ));
    }

    public function testSet()
    {
        $val = $this->val('500 usd');

        $val->VALUE = 100;
        $this->assertEquals('100 eur', $val->dump(false));

        $val->value = '100usd';
        $this->assertEquals('100 usd', $val->dump(false));

        $val->value = [100, 'rub'];
        $this->assertEquals('100 rub', $val->dump(false));

        $val->rule = 'eur';
        $this->assertEquals('2 eur', $val->dump(false));
    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    public function testSetUndefined()
    {
        $val = $this->val('500 usd');

        $val->someUndefined = 100;
        $this->assertEquals('500 usd', $val->dump(false));
    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
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
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    public function testCallUndefined()
    {
        $this->val('1 eur')->undefined();
    }

    public function testInvoke()
    {
        $val = $this->val('1 eur');

        $this->batchEquals(array(
            ['2 usd', $val('usd')->dump(false)],
            ['50 eur', $val('50')->dump(false)],
            ['100 rub', $val('100 rub')->dump(false)],
            ['100 uah', $val('100', 'uah')->dump(false)],
        ));
    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    public function testInvokeErrorNoArgs()
    {
        $val = $this->val('1 eur');
        $val();
    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
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

        $this->assertEquals(true, $val1->id() !== $val2->id());

        $val1 = $this->val(1);
        $val2 = $val1->add(2, false);

        $this->assertEquals(true, $val1->id() === $val2->id());
    }
}
