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
 * Class converterTest
 * @package SmetDenis\SimpleTypes
 */
class converterTest extends PHPUnit
{

    public function testCheckExists()
    {
        $this->batchEqualDumps(array(
            ['1 eur', $this->val('1')->dump(false)], // eur is default
            ['1 eur', $this->val('1 eur')->dump(false)],
            ['1 usd', $this->val('1 usd')->dump(false)],
            ['1 rub', $this->val('1 rub')->dump(false)],
            ['1 uah', $this->val('1 uah')->dump(false)],
            ['1 byr', $this->val('1 byr')->dump(false)],
        ));

    }

    public function testSimple()
    {
        $val = $this->val('1.25 usd');

        $this->batchEquals(array(
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

    public function testUndefinedRuleSilent()
    {
        $this->assertEquals('1.25 eur', $this->val('1.25 qwe')->dump(false));
    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    public function testUndefinedRuleFatal()
    {
        $this->val('1.25 usd')->convert('qwerty');
    }

    public function testEmptyRule()
    {
        $this->assertEquals(true, $this->val('1.25 usd')->convert('')->isRule('usd'));
        $this->assertEquals(true, $this->val('1.25 usd')->convert('', true)->isRule('usd'));
    }

}
