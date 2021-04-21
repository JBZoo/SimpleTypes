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
 * Class CompareTest
 * @package JBZoo\PHPUnit
 */
class CompareTest extends PHPUnit
{
    public function testSimple()
    {
        isBatch([
            [true, val(1)->compare(1, '=')],
            [true, val(1)->compare(1, '==')],
            [true, val(1)->compare(1, '===')],
            [true, val(1)->compare(1, '>=')],
            [true, val(1)->compare(1, '<=')],

            [false, val(1)->compare(1, '<')],
            [false, val(1)->compare(1, '!=')],
            [false, val(1)->compare(1, '!==')],
            [false, val(1)->compare(1, '>')],
        ]);
    }

    public function testComplex()
    {
        $v1 = val(1.5);
        $v2 = val('5.6');

        isBatch([
            [false, $v1->compare($v2, ' =')],
            [true, $v1->compare($v2, '< ')],
            [true, $v1->compare($v2, ' <= ')],
            [false, $v1->compare($v2, ' >= ')],
            [false, $v1->compare($v2, '>')],
        ]);
    }

    public function testRules()
    {
        $usd = val('1 usd');
        $eur = val('1 eur');

        isBatch([
            [false, $usd->compare($eur, '=')],
            [true, $usd->compare($eur, '!=')],
            [true, $usd->compare($eur, '<')],
            [true, $usd->compare($eur, '<=')],
            [false, $usd->compare($eur, '>')],
            [false, $usd->compare($eur, '>=')],
        ]);

        // after convert
        $eur->convert('usd');
        $usd->convert('eur');

        isBatch([
            [false, $usd->compare($eur, '==')],
            [true, $usd->compare($eur, '!==')],
            [true, $usd->compare($eur, '<')],
            [true, $usd->compare($eur, '<=')],
            [false, $usd->compare($eur, '>')],
            [false, $usd->compare($eur, '>=')],
        ]);
    }

    public function testUndefined()
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('usd')->compare(0, 'undefined');
    }
}
