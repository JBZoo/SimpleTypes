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
 * Class compareTest
 * @package SmetDenis\SimpleTypes
 */
class compareTest extends PHPUnit
{

    function testSimple()
    {
        $this->_batchEquals(array(
            [true, $this->_(1)->compare(1, '=')],
            [true, $this->_(1)->compare(1, '==')],
            [true, $this->_(1)->compare(1, '===')],
            [true, $this->_(1)->compare(1, '>=')],
            [true, $this->_(1)->compare(1, '<=')],

            [false, $this->_(1)->compare(1, '<')],
            [false, $this->_(1)->compare(1, '!=')],
            [false, $this->_(1)->compare(1, '!==')],
            [false, $this->_(1)->compare(1, '>')],
        ));
    }

    function testComplex()
    {
        $v1 = $this->_(1.5);
        $v2 = $this->_('5.6');

        $this->_batchEquals(array(
            [false, $v1->compare($v2, '=')],
            [true, $v1->compare($v2, '<')],
            [true, $v1->compare($v2, '<=')],
            [false, $v1->compare($v2, '>=')],
            [false, $v1->compare($v2, '>')],
        ));
    }

    function testRules()
    {
        $usd = $this->_('1 usd');
        $eur = $this->_('1 eur');

        $this->_batchEquals(array(
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

        $this->_batchEquals(array(
            [false, $usd->compare($eur, '==')],
            [true, $usd->compare($eur, '!==')],
            [true, $usd->compare($eur, '<')],
            [true, $usd->compare($eur, '<=')],
            [false, $usd->compare($eur, '>')],
            [false, $usd->compare($eur, '>=')],
        ));
    }
}