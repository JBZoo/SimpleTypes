<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/../src/autoload.php');


/**
 * Class roundTest
 * @package SmetDenis\SimpleTypes
 */
class roundTest extends PHPUnit
{

    function testNone()
    {
        $value = '123.456789';
        $rType = Formatter::ROUND_NONE;

        $this->_batchEquals(array(
            ['123.456789 eur', $this->_($value)->round(-3, $rType)->dump(false)],
            ['123.456789 eur', $this->_($value)->round(-2, $rType)->dump(false)],
            ['123.456789 eur', $this->_($value)->round(-1, $rType)->dump(false)],
            ['123.456789 eur', $this->_($value)->round(0, $rType)->dump(false)],
            ['123.456789 eur', $this->_($value)->round(1, $rType)->dump(false)],
            ['123.456789 eur', $this->_($value)->round(2, $rType)->dump(false)],
            ['123.456789 eur', $this->_($value)->round(3, $rType)->dump(false)],
        ));

    }

    function testClassic()
    {
        $value = '123.456789';
        $rType = Formatter::ROUND_CLASSIC;

        $this->_batchEquals(array(
            ['0 eur', $this->_($value)->round(-3, $rType)->dump(false)],
            ['100 eur', $this->_($value)->round(-2, $rType)->dump(false)],
            ['120 eur', $this->_($value)->round(-1, $rType)->dump(false)],
            ['123 eur', $this->_($value)->round(0, $rType)->dump(false)],
            ['123.5 eur', $this->_($value)->round(1, $rType)->dump(false)],
            ['123.46 eur', $this->_($value)->round(2, $rType)->dump(false)],
            ['123.457 eur', $this->_($value)->round(3, $rType)->dump(false)],
        ));

    }

    function testCeil()
    {
        $value = '111.111111';
        $rType = Formatter::ROUND_CEIL;

        $this->_batchEquals(array(
            ['1000 eur', $this->_($value)->round(-3, $rType)->dump(false)],
            ['200 eur', $this->_($value)->round(-2, $rType)->dump(false)],
            ['120 eur', $this->_($value)->round(-1, $rType)->dump(false)],
            ['112 eur', $this->_($value)->round(0, $rType)->dump(false)],
            ['111.2 eur', $this->_($value)->round(1, $rType)->dump(false)],
            ['111.12 eur', $this->_($value)->round(2, $rType)->dump(false)],
            ['111.112 eur', $this->_($value)->round(3, $rType)->dump(false)],
        ));

    }

    function testFloor()
    {
        $value = '999.999999';
        $rType = Formatter::ROUND_FLOOR;

        $this->_batchEquals(array(
            ['0 eur', $this->_($value)->round(-3, $rType)->dump(false)],
            ['900 eur', $this->_($value)->round(-2, $rType)->dump(false)],
            ['990 eur', $this->_($value)->round(-1, $rType)->dump(false)],
            ['999 eur', $this->_($value)->round(0, $rType)->dump(false)],
            ['999.9 eur', $this->_($value)->round(1, $rType)->dump(false)],
            ['999.99 eur', $this->_($value)->round(2, $rType)->dump(false)],
            ['999.999 eur', $this->_($value)->round(3, $rType)->dump(false)],
        ));

    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    function testUndefinedMode()
    {
        $this->_('123.456789')->round(-3, 'undefined');
    }


}