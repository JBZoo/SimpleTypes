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
 * Class roundTest
 * @package SmetDenis\SimpleTypes
 */
class roundTest extends PHPUnit
{

    public function testNone()
    {
        $value = '123.456789';
        $rType = Formatter::ROUND_NONE;

        $this->batchEquals(array(
            ['123.456789 eur', $this->val($value)->round(-3, $rType)->dump(false)],
            ['123.456789 eur', $this->val($value)->round(-2, $rType)->dump(false)],
            ['123.456789 eur', $this->val($value)->round(-1, $rType)->dump(false)],
            ['123.456789 eur', $this->val($value)->round(0, $rType)->dump(false)],
            ['123.456789 eur', $this->val($value)->round(1, $rType)->dump(false)],
            ['123.456789 eur', $this->val($value)->round(2, $rType)->dump(false)],
            ['123.456789 eur', $this->val($value)->round(3, $rType)->dump(false)],
        ));
    }

    public function testClassic()
    {
        $value = '123.456789';
        $rType = Formatter::ROUND_CLASSIC;

        $this->batchEquals(array(
            ['0 eur', $this->val($value)->round(-3, $rType)->dump(false)],
            ['100 eur', $this->val($value)->round(-2, $rType)->dump(false)],
            ['120 eur', $this->val($value)->round(-1, $rType)->dump(false)],
            ['123 eur', $this->val($value)->round(0, $rType)->dump(false)],
            ['123.5 eur', $this->val($value)->round(1, $rType)->dump(false)],
            ['123.46 eur', $this->val($value)->round(2, $rType)->dump(false)],
            ['123.457 eur', $this->val($value)->round(3, $rType)->dump(false)],
        ));
    }

    public function testCeil()
    {
        $value = '111.111111';
        $rType = Formatter::ROUND_CEIL;

        $this->batchEquals(array(
            ['1000 eur', $this->val($value)->round(-3, $rType)->dump(false)],
            ['200 eur', $this->val($value)->round(-2, $rType)->dump(false)],
            ['120 eur', $this->val($value)->round(-1, $rType)->dump(false)],
            ['112 eur', $this->val($value)->round(0, $rType)->dump(false)],
            ['111.2 eur', $this->val($value)->round(1, $rType)->dump(false)],
            ['111.12 eur', $this->val($value)->round(2, $rType)->dump(false)],
            ['111.112 eur', $this->val($value)->round(3, $rType)->dump(false)],
        ));
    }

    public function testFloor()
    {
        $value = '999.999999';
        $rType = Formatter::ROUND_FLOOR;

        $this->batchEquals(array(
            ['0 eur', $this->val($value)->round(-3, $rType)->dump(false)],
            ['900 eur', $this->val($value)->round(-2, $rType)->dump(false)],
            ['990 eur', $this->val($value)->round(-1, $rType)->dump(false)],
            ['999 eur', $this->val($value)->round(0, $rType)->dump(false)],
            ['999.9 eur', $this->val($value)->round(1, $rType)->dump(false)],
            ['999.99 eur', $this->val($value)->round(2, $rType)->dump(false)],
            ['999.999 eur', $this->val($value)->round(3, $rType)->dump(false)],
        ));
    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    public function testUndefinedMode()
    {
        $this->val('123.456789')->round(-3, 'undefined');
    }
}
