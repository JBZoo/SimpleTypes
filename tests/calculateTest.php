<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/../src/autoload.php');


/**
 * Class calculateTest
 * @package SmetDenis\SimpleTypes
 */
class calculateTest extends PHPUnit
{

    function testAdd()
    {
        $val = $this->_();

        $this->_batchEqualDumps(array(
            ['0 eur', $val->dump(false)],
            ['1 eur', $val->add('1')->dump(false)],
            ['0 eur', $val->add('-1')->dump(false)],
            ['10 eur', $val->add('10')->dump(false)],
            ['15 eur', $val->add('10 usd')->dump(false)],
            ['16 eur', $val->add(1)->dump(false)],
            ['15 eur', $val->add(-1)->dump(false)],
            ['15.56 eur', $val->add(0.56)->dump(false)],
            ['14.99 eur', $val->add(-0.57)->dump(false)],
        ));
    }

    function testSubtract()
    {
        $val = $this->_();

        $this->_batchEqualDumps(array(
            ['0 eur', $val->dump(false)],
            ['-1 eur', $val->subtract('1')->dump(false)],
            ['0 eur', $val->subtract('-1')->dump(false)],
            ['-10 eur', $val->subtract('10')->dump(false)],
            ['-15 eur', $val->subtract('10 usd')->dump(false)],
            ['-16 eur', $val->subtract(1)->dump(false)],
            ['-15 eur', $val->subtract(-1)->dump(false)],
            ['-15.56 eur', $val->subtract(0.56)->dump(false)],
            ['-16.13 eur', $val->subtract(0.57)->dump(false)],
        ));
    }

    function testPercentAddAndSubtract()
    {
        $val = $this->_(100);

        $this->_batchEqualDumps(array(
            ['100 eur', $val->dump(false)],

            // plus
            ['110 eur', $val->add('10%')->dump(false)],
            ['99 eur', $val->add('-10%')->dump(false)],
            ['108.9 eur', $val->add('+10%')->dump(false)],

            // minus
            ['-980.1 eur', $val->subtract('+1000%')->dump(false)],
            ['-2411.046 eur', $val->add('146%')->dump(false)],
        ));
    }

    function testAddAndSubtract()
    {
        $val = $this->_('100 eur');

        $val
            ->add(-10)
            ->add('50%')
            ->subtract(200)
            ->add('-50%')
            ->add($this->_('100 eur'))
            ->add(-10)
            ->subtract(-200);

        $this->assertEquals($val->dump(false), '257.5 eur');
    }

    function testInvert()
    {
        $val = $this->_('1 eur');

        $this->_batchEqualDumps(array(
            ['-1 eur', $val->invert()->dump(false)],
            ['1 eur', $val->invert()->dump(false)],
        ));

        $val = $this->_();

        $this->_batchEqualDumps(array(
            ['0 eur', $val->invert()->dump(false)],
            ['0 eur', $val->invert()->dump(false)],
        ));
    }

    function testPositiveAndNegative()
    {
        $val = $this->_('1 eur');

        $this->_batchEqualDumps(array(
            ['1 eur', $val->positive()->dump(false)],
            ['-1 eur', $val->negative()->dump(false)],
            ['-1 eur', $val->negative()->dump(false)],
            ['1 eur', $val->positive()->dump(false)],
        ));
    }

    function testMultiply()
    {
        $val = $this->_('1 eur');

        $this->_batchEqualDumps(array(
            ['1 eur', $val->multiply('1')->dump(false)],
            ['10 eur', $val->multiply('10')->dump(false)],
            ['-10 eur', $val->multiply('-1')->dump(false)],
            ['5.6 eur', $val->multiply('-.56')->dump(false)],
        ));
    }

    function testPercent()
    {
        $discountSave = $this->_('20 eur');
        $itemPrice    = $this->_('100 eur');

        $this->_batchEqualDumps(array(
            ['20 %', $discountSave->percent($itemPrice)->dump(false)],
            ['40 %', $this->_('10 eur')->percent('50 usd')->dump(false)],
        ));
    }

    function testFunction()
    {
        $val = $this->_('100 eur');

        $val
            ->customFunc(function ($value) {
                $value
                    ->multiply(6.5)
                    ->add('55%')
                    ->negative('55%');
            })
            ->customFunc(function ($value) {
                $value
                    ->add([50, 'usd'])
                    ->convert('byr');
            });

        $this->assertEquals('20650000 byr', $val->dump(false));
    }


}