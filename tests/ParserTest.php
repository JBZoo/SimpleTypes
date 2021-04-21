<?php

/**
 * JBZoo Toolbox - SimpleTypes
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    SimpleTypes
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/SimpleTypes
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

/**
 * Class parserTest
 * @package JBZoo\SimpleTypes
 */
class ParserTest extends PHPUnit
{
    public function testEmpty()
    {
        $empty = '0 eur';

        batchEqualDumps(array(
            // empty
            array($empty),
            array($empty, null),
            array($empty, 0),
            array($empty, ''),

            // spaces
            array($empty, ' '),
            array($empty, '  '),
            array($empty, "\t"),
            array($empty, "\t "),
            array($empty, " \t "),
            array($empty, " \n "),
            array($empty, " \n\r "),
            array($empty, " \r\n "),

            // arrays
            array($empty, array()),
            array($empty, array(null, null)),
            array($empty, array(0)),
            array($empty, array(0, '')),
            array($empty, array(0, "")),
        ));
    }

    public function testSimple()
    {
        batchEqualDumps(array(
            // int
            array('1 eur', 1),
            array('-1 eur', -1),

            // float
            array('2 eur', 2.0),
            array('3.55 eur', 3.55),
            array('0.57 eur', .57),
            array('-2 eur', '-2,'),
            array('-3.55 eur', '-3,55'),
            array('-0.57 eur', '-,57'),

            // big
            array('1000000.99999 eur', '1 000 000.999 99'),

            // huge
            array('1.0E+18 eur', '1.0e+18'),
        ));
    }

    public function testRule()
    {
        batchEqualDumps(array(
            array('0 eur', 'eur'),
            array('1 eur', '1eur'),
            array('1 eur', '1EUR'),
            array('1 eur', '1eUr'),
            array('1 eur', '1 eur '),
            array('1 eur', '1eur '),
            array('1 eur', '1 eur'),
            array('1 eur', '1 eur'),
            array('1 usd', "1\tusd"),
            array('1.0E+18 eur', '1.0e+18 EUR '),

            // reverse
            array('1 eur', 'eur1'),
            array('1 eur', 'eur 1'),
            array('1 eur', ' eur 1'),
            array('1 eur', ' eur 1 '),
            array('1 eur', 'eur 1 '),
        ));
    }

    public function testRound()
    {
        batchEqualDumps(array(
            array('0.1 eur', '.1'),
            array('0.01 eur', '.01'),
            array('0.001 eur', '.001'),
            array('0.0001 eur', '.0001'),
            array('1.0E-5 eur', '.00001'),
            array('1.0E-6 eur', '.000001'),
            array('1.0E-7 eur', '.0000001'),
            array('1.0E-8 eur', '.00000001'),
            array('1.0E-8 eur', '.000000009'),
            array('1.0E-8 eur', '.0000000099'),
            array('1.0E-8 eur', '.000000005'),
            array('0 eur', '.000000001'),
            array('0 eur', '.000000004'),
        ));
    }

    public function testComplex()
    {
        batchEqualDumps(array(
            array('-123.456 usd', ' - 1 2 3 . 4 5 6 usd '),
            array('-123.456 usd', array(' - 1 2 3 , 4 5 6 eur', 'usd')),
            array('-123.456 usd', array(' - 1 2 3 . 4 5 6 eur', 'usd')),

            // insane
            array('-987654321.123 eur', 'some number - 9 8 7 6 5 4 3 2 1,      1  2 3   eur '),
        ));
    }

    public function testUndefinedRule()
    {
        isTrue(val('1 undefined')->isRule('eur'));
    }
}
