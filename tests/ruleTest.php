<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/../src/autoload.php');


/**
 * Class formatterTest
 * @package SmetDenis\SimpleTypes
 */
class formatterTest extends PHPUnit
{

    function testChange()
    {
        $val1 = $this->_('50000.789 usd');

        $this->_batchEquals(array(
            ['$50 000.79', $val1->text()],
            ['50 000.79$', $val1->changeRule('usd', array('format_positive' => '%v%s'))->text()],
        ));
    }

    function testAdd()
    {
        $val = $this->_('50000 usd');

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
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    function testRemove()
    {
        $this->_('50000 usd')
            ->removeRule('rub')
            ->convert('rub'); // Exception!
    }

    function testDependenceChanges()
    {
        $val1 = $this->_('usd')->changeRule('usd', array('format_positive' => '%v%s'));
        $val2 = $this->_('usd');

        $this->assertEquals('0.00$', $val1->text());
        $this->assertEquals('$0.00', $val2->text());
    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    function testDependenceAdd()
    {
        $this->_('1 usd')->addRule('qwe', array())->convert('qwe');
        $this->_('1 usd')->convert('qwe');
    }

    function testDependenceRemove()
    {
        $this->_('1 usd')->removeRule('rub');
        $this->assertEquals('25 rub', $this->_('1 usd')->convert('rub')->dump(false));
    }
}