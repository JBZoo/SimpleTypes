<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class compareTest
 * @package SmetDenis\SimpleTypes
 */
class compareTest extends PHPUnit
{

    public function testSimple()
    {
        $this->batchEquals(array(
            [true, $this->val(1)->compare(1, '=')],
            [true, $this->val(1)->compare(1, '==')],
            [true, $this->val(1)->compare(1, '===')],
            [true, $this->val(1)->compare(1, '>=')],
            [true, $this->val(1)->compare(1, '<=')],

            [false, $this->val(1)->compare(1, '<')],
            [false, $this->val(1)->compare(1, '!=')],
            [false, $this->val(1)->compare(1, '!==')],
            [false, $this->val(1)->compare(1, '>')],
        ));
    }

    public function testComplex()
    {
        $v1 = $this->val(1.5);
        $v2 = $this->val('5.6');

        $this->batchEquals(array(
            [false, $v1->compare($v2, ' =')],
            [true, $v1->compare($v2, '< ')],
            [true, $v1->compare($v2, ' <= ')],
            [false, $v1->compare($v2, ' >= ')],
            [false, $v1->compare($v2, '>')],
        ));
    }

    public function testRules()
    {
        $usd = $this->val('1 usd');
        $eur = $this->val('1 eur');

        $this->batchEquals(array(
            [false, $usd->compare($eur, '=')],
            [true, $usd->compare($eur, '!=')],
            [true, $usd->compare($eur, '<')],
            [true, $usd->compare($eur, '<=')],
            [false, $usd->compare($eur, '>')],
            [false, $usd->compare($eur, '>=')],
        ));

        // after convert
        $eur->convert('usd');
        $usd->convert('eur');

        $this->batchEquals(array(
            [false, $usd->compare($eur, '==')],
            [true, $usd->compare($eur, '!==')],
            [true, $usd->compare($eur, '<')],
            [true, $usd->compare($eur, '<=')],
            [false, $usd->compare($eur, '>')],
            [false, $usd->compare($eur, '>=')],
        ));
    }

    /**
     * @expectedException \SmetDenis\SimpleTypes\Exception
     */
    public function testUndefined()
    {
        $this->val('usd')->compare(0, 'undefined');
    }

}
