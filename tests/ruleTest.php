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
 * Class formatterTest
 * @package JBZoo\SimpleTypes
 */
class FormatterTest extends PHPUnit
{

    public function testChange()
    {
        $val1 = $this->val('50000.789 usd');

        $this->batchEquals(array(
            array('$50 000.79', $val1->text()),
            array('50 000.79$', $val1->changeRule('usd', array('format_positive' => '%v%s'))->text()),
        ));
    }

    public function testAdd()
    {
        $val = $this->val('50000 usd');

        $val->addRule('qwe', array(
            'symbol'          => 'Q',
            'num_decimals'    => '0',
            'decimal_sep'     => ',',
            'thousands_sep'   => '.',
            'format_positive' => '%v %s',
            'format_negative' => '-%v %s',
            'rate'            => 0.15,
        ));

        $this->assertEquals('$50 000.00', $val->text());
        $this->assertEquals('166.667 Q', $val->convert('qwe')->text());
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testRemove()
    {
        $val = $this->val('50000 usd');
        $val->removeRule('rub');
        $val->convert('rub'); // Exception!
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testSetEmptyRule()
    {
        $this->val('50000 usd')
            ->addRule(' '); // Exception!
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testAddExists()
    {
        $this->val('50000 usd')->addRule('rub');
    }

    public function testGetRule()
    {
        $rule = $this->val('50000 rub')->getRuleData('usd');
        $this->assertEquals('$', $rule['symbol']);
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testGetRuleUndefined()
    {
        $this->val('50000 rub')->getRuleData('undefined');
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testChangeUndefined()
    {
        $this->val('50000 usd')
            ->changeRule('undefined', array()); // Exception!
    }

    public function testDependenceChanges()
    {
        $val1 = $this->val('usd')->changeRule('usd', array('format_positive' => '%v%s'));
        $val2 = $this->val('usd');

        $this->assertEquals('0.00$', $val1->text());
        $this->assertEquals('$0.00', $val2->text());
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testDependenceAdd()
    {
        $this->val('1 usd')->addRule('qwe', array())->convert('qwe');
        $this->val('1 usd')->convert('qwe');
    }

    public function testDependenceRemove()
    {
        $this->val('1 usd')->removeRule('rub');
        $this->assertEquals('25 rub', $this->val('1 usd')->convert('rub')->dump(false));
    }

    public function testIsRule()
    {
        $val = $this->val('1 usd');

        $this->assertEquals(true, $val->isRule('USD'));

        $val->convert('RUB');
        $this->assertEquals(false, $val->isRule('USD'));

    }

    public function testGetRules()
    {
        $val = $this->val();
        $this->assertArrayHasKey('rub', $val->getRules());
        $this->assertArrayHasKey('usd', $val->getRules());
        $this->assertArrayHasKey('uah', $val->getRules());
        $this->assertArrayHasKey('eur', $val->getRules());
        $this->assertArrayHasKey('byr', $val->getRules());
    }
}
