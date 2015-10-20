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

namespace JBZoo\PHPUnit;

/**
 * Class formatterTest
 * @package JBZoo\SimpleTypes
 */
class FormatterTest extends PHPUnit
{

    public function testChange()
    {
        $val1 = val('50000.789 usd');

        isBatch(array(
            array('$50 000.79', $val1->text()),
            array('50 000.79$', $val1->changeRule('usd', array('format_positive' => '%v%s'))->text()),
        ));
    }

    public function testAdd()
    {
        $val = val('50000 usd');

        $val->addRule('qwe', array(
            'symbol'          => 'Q',
            'num_decimals'    => '0',
            'decimal_sep'     => ',',
            'thousands_sep'   => '.',
            'format_positive' => '%v %s',
            'format_negative' => '-%v %s',
            'rate'            => 0.15,
        ));

        is('$50 000.00', $val->text());
        is('166.667 Q', $val->convert('qwe')->text());
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testRemove()
    {
        $val = val('50000 usd');
        $val->removeRule('rub');
        $val->convert('rub'); // Exception!
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testSetEmptyRule()
    {
        val('50000 usd')
            ->addRule(' '); // Exception!
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testAddExists()
    {
        val('50000 usd')->addRule('rub');
    }

    public function testGetRule()
    {
        $rule = val('50000 rub')->getRuleData('usd');
        is('$', $rule['symbol']);
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testGetRuleUndefined()
    {
        val('50000 rub')->getRuleData('undefined');
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testChangeUndefined()
    {
        val('50000 usd')
            ->changeRule('undefined', array()); // Exception!
    }

    public function testDependenceChanges()
    {
        $val1 = val('usd')->changeRule('usd', array('format_positive' => '%v%s'));
        $val2 = val('usd');

        is('0.00$', $val1->text());
        is('$0.00', $val2->text());
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
     */
    public function testDependenceAdd()
    {
        val('1 usd')->addRule('qwe', array())->convert('qwe');
        val('1 usd')->convert('qwe');
    }

    public function testDependenceRemove()
    {
        val('1 usd')->removeRule('rub');
        is('25 rub', val('1 usd')->convert('rub')->dump(false));
    }

    public function testIsRule()
    {
        $val = val('1 usd');

        is(true, $val->isRule('USD'));

        $val->convert('RUB');
        is(false, $val->isRule('USD'));

    }

    public function testGetRules()
    {
        $val = val();
        isKey('rub', $val->getRules());
        isKey('usd', $val->getRules());
        isKey('uah', $val->getRules());
        isKey('eur', $val->getRules());
        isKey('byr', $val->getRules());
    }
}
