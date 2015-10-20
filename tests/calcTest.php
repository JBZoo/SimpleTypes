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
 * Class calcTest
 * @package JBZoo\SimpleTypes
 */
class CalcTest extends PHPUnit
{
    public function testAdd()
    {
        $val = $this->val();

        $this->batchEqualDumps(array(
            array('0 eur', $val->dump(false)),
            array('1 eur', $val->add('1')->dump(false)),
            array('0 eur', $val->add('-1')->dump(false)),
            array('10 eur', $val->add('10')->dump(false)),
            array('15 eur', $val->add('10 usd')->dump(false)),
            array('16 eur', $val->add(1)->dump(false)),
            array('15 eur', $val->add(-1)->dump(false)),
            array('15.56 eur', $val->add(0.56)->dump(false)),
            array('14.99 eur', $val->add(-0.57)->dump(false)),
        ));
    }

    public function testSubtract()
    {
        $val = $this->val();

        $this->batchEqualDumps(array(
            array('0 eur', $val->dump(false)),
            array('-1 eur', $val->subtract('1')->dump(false)),
            array('0 eur', $val->subtract('-1')->dump(false)),
            array('-10 eur', $val->subtract('10')->dump(false)),
            array('-15 eur', $val->subtract('10 usd')->dump(false)),
            array('-16 eur', $val->subtract(1)->dump(false)),
            array('-15 eur', $val->subtract(-1)->dump(false)),
            array('-15.56 eur', $val->subtract(0.56)->dump(false)),
            array('-16.13 eur', $val->subtract(0.57)->dump(false)),
        ));
    }

    public function testPercentAddAndSubtract()
    {
        $val = $this->val(100);

        $this->batchEqualDumps(array(
            array('100 eur', $val->dump(false)),

            // plus
            array('110 eur', $val->add('10%')->dump(false)),
            array('99 eur', $val->add('-10%')->dump(false)),
            array('108.9 eur', $val->add('+10%')->dump(false)),

            // minus
            array('-980.1 eur', $val->subtract('+1000%')->dump(false)),
            array('-2411.046 eur', $val->add('146%')->dump(false)),
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

        $this->assertEquals('3 %', $this->val('1%')->add('2%')->dump(false));
    }


    public function testInvert()
    {
        $val = $this->val('1 eur');

        $this->batchEqualDumps(array(
            array('-1 eur', $val->invert()->dump(false)),
            array('1 eur', $val->invert()->dump(false)),
        ));

        $val = $this->val();

        $this->batchEqualDumps(array(
            array('0 eur', $val->invert()->dump(false)),
            array('0 eur', $val->invert()->dump(false)),
        ));
    }

    public function testPositiveAndNegative()
    {
        $val = $this->val('1 eur');

        $this->batchEqualDumps(array(
            array('1 eur', $val->positive()->dump(false)),
            array('-1 eur', $val->negative()->dump(false)),
            array('-1 eur', $val->negative()->dump(false)),
            array('1 eur', $val->positive()->dump(false)),
        ));
    }

    public function testMultiply()
    {
        $val = $this->val('1 eur');

        $this->batchEqualDumps(array(
            array('1 eur', $val->multiply('1')->dump(false)),
            array('10 eur', $val->multiply('10')->dump(false)),
            array('-10 eur', $val->multiply('-1')->dump(false)),
            array('5.6 eur', $val->multiply('-.56')->dump(false)),
        ));
    }

    public function testDivision()
    {
        $val = $this->val('360 eur');

        $this->batchEqualDumps(array(
            array('3000 eur', $val->division(.12)->dump(false)),
            array('100 eur', $val->division(30)->dump(false)),
        ));
    }

    public function testPercent()
    {
        $discountSave = $this->val('20 eur');
        $itemPrice    = $this->val('100 eur');

        $this->batchEqualDumps(array(
            array('20 %', $discountSave->percent($itemPrice)->dump(false)),
            array('40 %', $this->val('10 eur')->percent('50 usd')->dump(false)),
            array('60 %', $this->val('10 eur')->percent('50 usd', true)->dump(false)),
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
                    ->add(array(50, 'usd'))
                    ->convert('byr');
            });

        $this->assertEquals('20650000 byr', $val->dump(false));
    }

    public function testChecks()
    {
        $val = $this->val('-1 usd');

        $this->assertEquals(true, $val->isNegative());
        $this->assertEquals(false, $val->isPositive());
        $this->assertEquals(false, $val->isEmpty());

        $val->set(1);
        $this->assertEquals(false, $val->isNegative());
        $this->assertEquals(true, $val->isPositive());
        $this->assertEquals(false, $val->isEmpty());

        $val->setEmpty();
        $this->assertEquals(false, $val->isNegative());
        $this->assertEquals(false, $val->isPositive());
        $this->assertEquals(true, $val->isEmpty());
    }

    public function testAbs()
    {
        $this->assertEquals('1 eur', $this->val('-1 eur')->abs()->dump(false));
        $this->assertEquals('1 eur', $this->val('1 eur')->abs()->dump(false));
        $this->assertEquals('0 eur', $this->val('0 eur')->abs()->dump(false));
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testImpossibleAdd1()
    {
        $this->val('1 %')->add('1 usd');
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testNoValidTypes()
    {
        $money  = new Money('1 usd', new ConfigMoney());
        $weight = new Weight('1 kg', new ConfigTestWeight());
        $money->add($weight);
    }
}
