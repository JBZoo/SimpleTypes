<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/../src/autoload.php');


/**
 * Class magicTest
 * @package SmetDenis\SimpleTypes
 */
class magicTest extends PHPUnit
{

    function testSerializing()
    {
        $valBefore = $this->_('500 usd');
        $valString = serialize($valBefore);
        $valAfter  = unserialize($valString)->convert('eur');

        $this->_batchEquals(array(
            ['500 usd', $valBefore->dump(false)],
            ['250 eur', $valAfter->dump(false)],
            [true, $valBefore->compare($valAfter)],
        ));

    }

    function testClone()
    {
        $original = $this->_('500 usd');
        $clone    = clone $original;
        $clone->convert('eur');

        $this->_batchEquals(array(
            ['500 usd', $original->dump(false)],
            ['250 eur', $clone->dump(false)],
            [true, $original->compare($clone)],
        ));
    }

    function testString()
    {
        $val = $this->_('500 usd');

        $this->_batchEquals(array(
            ['$500.00', (string)$val],
            ['$500.00', '' . $val],
            ['$500.00', $val->__toString()],
        ));
    }

    function testGet()
    {
        $val = $this->_('500 usd');

        $this->_batchEquals(array(
            [null, $val->someUndefinedProp],
            [500.0, $val->value],
            ['usd', $val->rule],
        ));
    }

    function testSet()
    {
        $val = $this->_('500 usd');

        $val->someUndefined = 100;
        $this->assertEquals('500 usd', $val->dump(false));

        $val->VALUE = 100;
        $this->assertEquals('100 eur', $val->dump(false));

        $val->value = '100usd';
        $this->assertEquals('100 usd', $val->dump(false));

        $val->value = [100, 'rub'];
        $this->assertEquals('100 rub', $val->dump(false));

        $val->rule = 'eur';
        $this->assertEquals('2 eur', $val->dump(false));
    }

    function testCall()
    {
        $val = $this->_('1 eur');
        $this->assertEquals($val->val('rub'), $val->value('rub'));
    }

    function testInvoke()
    {
        $val = $this->_('1 eur');

        $this->_batchEquals(array(
            ['2 usd', $val('usd')->dump(false)],
            ['50 eur', $val('50')->dump(false)],
            ['100 rub', $val('100 rub')->dump(false)],
            ['100 uah', $val('100', 'uah')->dump(false)],
        ));

    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    function testInvokeErrorNoArgs()
    {
        $val = $this->_('1 eur');
        $val();
    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    function testInvokeErrorTooManyArgs()
    {
        $val = $this->_('1 eur');
        $val(1, 2, 3);
    }

}