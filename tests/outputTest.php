<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package    SimpleTypes
 * @author     Denis Smetannikov <denis@jbzoo.com>
 * @copyright  2015 Denis Smetannikov <denis@jbzoo.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link       http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;


/**
 * Class outputTest
 * @package SmetDenis\SimpleTypes
 */
class outputTest extends PHPUnit
{

    function testText()
    {
        $this->_batchEquals(array(
            // eur
            ['10 000.67 €', $this->_('10000.666 eur')->text()],
            ['-10 000.67 €', $this->_('-10000.666 eur')->text()],

            // usd
            ['$10 000.67', $this->_('10000.666 usd')->text()],
            ['-$10 000.67', $this->_('-10000.666 usd')->text()],

            // rub
            ['10 000,67 руб.', $this->_('10000.666 rub')->text()],
            ['-10 000,67 руб.', $this->_('-10000.666 rub')->text()],

            // uah
            ['10 000,67 грн.', $this->_('10000.666 uah')->text()],
            ['-10 000,67 грн.', $this->_('-10000.666 uah')->text()],

            // byr
            ['10 100 Br', $this->_('10000.666 byr')->text()],
            ['-10 000 Br', $this->_('-10000.666 byr')->text()],

            // %
            ['10.67%', $this->_('10.666 %')->text()],
            ['-10.67%', $this->_('-10.666 %')->text()],

            // with converting
            ['$2.00', $this->_('1 eur')->text('usd')],
            ['0.50 €', $this->_('1 usd')->text('eur')],
        ));
    }

    function testDump()
    {
        $this->assertRegExp('#10000.66666667 uah; id=[0-9]*#i', $this->_('10000.666666666 uah')->dump());
        $this->assertEquals('10000.666 uah', $this->_('10000.666 uah')->dump(false));
    }

    function testData()
    {
        $this->_batchEquals(array(
            [['10000.666', 'uah'], $this->_('10000.666 uah')->data()],
            [['10000.666', 'uah'], $this->_('10000.666 uah')->data(false)],
            ['10000.666 uah', $this->_('10000.666 uah')->data(true)],
        ));
    }

    function testNoStyle()
    {
        $this->_batchEquals(array(
            ['10 000,67', $this->_('10000.666 uah')->noStyle()],
        ));
    }


}