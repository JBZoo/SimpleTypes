<?php
/**
 * SimpleTypes
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class DegreeTypeTest
 * @package SmetDenis\SimpleTypes
 */
class DegreeTypeTest extends TypeTest
{

    protected $type = 'degree';

    public function testSimple()
    {
        $val = $this->val('180 d');

        $this->batchEquals(array(
            array('180 d', $val->dump(false)),
            array('1 r', $val->convert('r')->dump(false)),
            array('200 g', $val->convert('g')->dump(false)),
            array('0.5 t', $val->convert('t')->dump(false)),
        ));
    }

    public function testMoreThan360()
    {
        $val = $this->val('1.5 r');

        $this->batchEquals(array(
            array('270 d', $val->convert('d')->dump(false)),
            array('1.5 r', $val->convert('r')->dump(false)),
            array('300 g', $val->convert('g')->dump(false)),
            array('0.75 t', $val->convert('t')->dump(false)),
        ));
    }

    public function testLessThan0()
    {
        $val = $this->val('-1 r');

        $this->batchEquals(array(
            array('-180 d', $val->convert('d')->dump(false)),
            array('-1 r', $val->convert('r')->dump(false)),
            array('-200 g', $val->convert('g')->dump(false)),
            array('-0.5 t', $val->convert('t')->dump(false)),
        ));
    }

    public function testRemoveCirclesDeg()
    {
        $this->assertEquals('180 d', $this->val('540 d')->removeCircles()->dump(false));
    }

    public function testRemoveCirclesNegativeRad()
    {
        $this->assertEquals('-1 r', $this->val('-5 r')->removeCircles()->dump(false));
    }
}
