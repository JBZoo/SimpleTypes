<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class calcTest
 * @package SmetDenis\SimpleTypes
 */
class calcTest extends PHPUnit
{
    public function testAdd()
    {
        $val = $this->val();

        $this->batchEqualDumps(array(
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

    public function testSubtract()
    {
        $val = $this->val();

        $this->batchEqualDumps(array(
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

    public function testPercentAddAndSubtract()
    {
        $val = $this->val(100);

        $this->batchEqualDumps(array(
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

    public function testAddAndSubtract()
    {
        $val = $this->val('100 eur');

        $val
            ->add(-10)
            ->add('50%')
            ->subtract(200)
            ->add('-50%')
            ->add($this->val('100 eur'))
            ->add(-10)
            ->subtract(-200);

        $this->assertEquals($val->dump(false), '257.5 eur');
    }

    public function testInvert()
    {
        $val = $this->val('1 eur');

        $this->batchEqualDumps(array(
            ['-1 eur', $val->invert()->dump(false)],
            ['1 eur', $val->invert()->dump(false)],
        ));

        $val = $this->val();

        $this->batchEqualDumps(array(
            ['0 eur', $val->invert()->dump(false)],
            ['0 eur', $val->invert()->dump(false)],
        ));
    }

    public function testPositiveAndNegative()
    {
        $val = $this->val('1 eur');

        $this->batchEqualDumps(array(
            ['1 eur', $val->positive()->dump(false)],
            ['-1 eur', $val->negative()->dump(false)],
            ['-1 eur', $val->negative()->dump(false)],
            ['1 eur', $val->positive()->dump(false)],
        ));
    }

    public function testMultiply()
    {
        $val = $this->val('1 eur');

        $this->batchEqualDumps(array(
            ['1 eur', $val->multiply('1')->dump(false)],
            ['10 eur', $val->multiply('10')->dump(false)],
            ['-10 eur', $val->multiply('-1')->dump(false)],
            ['5.6 eur', $val->multiply('-.56')->dump(false)],
        ));
    }

    public function testDivision()
    {
        $val = $this->val('360 eur');

        $this->batchEqualDumps(array(
            ['3000 eur', $val->division(.12)->dump(false)],
            ['100 eur', $val->division(30)->dump(false)],
        ));
    }

    public function testPercent()
    {
        $discountSave = $this->val('20 eur');
        $itemPrice    = $this->val('100 eur');

        $this->batchEqualDumps(array(
            ['20 %', $discountSave->percent($itemPrice)->dump(false)],
            ['40 %', $this->val('10 eur')->percent('50 usd')->dump(false)],
        ));
    }

    public function testFunction()
    {
        $val = $this->val('100 eur');

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
