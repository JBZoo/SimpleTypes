<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/../src/autoload.php');

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