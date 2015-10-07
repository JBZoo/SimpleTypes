<?php
/**
 * SimpleTypes
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
 * Class converterTest
 * @package JBZoo\SimpleTypes
 */
class ConverterTest extends PHPUnit
{

    public function testCheckExists()
    {
        $this->batchEqualDumps(array(
            array('1 eur', $this->val('1')->dump(false)), // eur is default
            array('1 eur', $this->val('1 eur')->dump(false)),
            array('1 usd', $this->val('1 usd')->dump(false)),
            array('1 rub', $this->val('1 rub')->dump(false)),
            array('1 uah', $this->val('1 uah')->dump(false)),
            array('1 byr', $this->val('1 byr')->dump(false)),
        ));

    }

    public function testSimple()
    {
        $val = $this->val('1.25 usd');

        $this->batchEquals(array(
            array(0.625, $val->val('eur')),
            array(1.25, $val->val('usd')),
            array('0.625 eur', $val->convert('eur')->dump(false)),
            array('1.25 usd', $val->convert('usd')->dump(false)),
            array('12500 byr', $val->convert('byr')->dump(false)),
            array('31.25 rub', $val->convert('rub')->dump(false)),
            array('0.625 eur', $val->convert('eur')->dump(false)),
            array('1.25 usd', $val->convert('usd')->dump(false)),
            array('1.25 usd', $val->convert('usd')->dump(false)),
        ));
    }

    public function testUndefinedRuleSilent()
    {
        $this->assertEquals('1.25 eur', $this->val('1.25 qwe')->dump(false));
    }

    /**
     * @expectedException \JBZoo\SimpleTypes\Exception
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
