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
 * Class converterTest
 * @package SmetDenis\SimpleTypes
 */
class converterTest extends PHPUnit
{

    function testCheckExists()
    {
        $this->_batchEqualDumps(array(
            ['1 eur', $this->_('1')->dump(false)], // eur is default
            ['1 eur', $this->_('1 eur')->dump(false)],
            ['1 usd', $this->_('1 usd')->dump(false)],
            ['1 rub', $this->_('1 rub')->dump(false)],
            ['1 uah', $this->_('1 uah')->dump(false)],
            ['1 byr', $this->_('1 byr')->dump(false)],
        ));

    }

    function testSimple()
    {
        $val = $this->_('1.25 usd');

        $this->_batchEquals(array(
            [0.625, $val->val('eur')],
            [1.25, $val->val('usd')],
            ['0.625 eur', $val->convert('eur')->dump(false)],
            ['1.25 usd', $val->convert('usd')->dump(false)],
            ['12500 byr', $val->convert('byr')->dump(false)],
            ['31.25 rub', $val->convert('rub')->dump(false)],
            ['0.625 eur', $val->convert('eur')->dump(false)],
            ['1.25 usd', $val->convert('usd')->dump(false)],
            ['1.25 usd', $val->convert('usd')->dump(false)],
        ));
    }

    function testUndefinedRuleSilent()
    {
        $this->assertEquals('1.25 eur', $this->_('1.25 qwe')->dump(false));
    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    function testUndefinedRuleFatal()
    {
        $this->_('1.25 usd')->convert('qwerty');
    }

}