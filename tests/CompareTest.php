<?php

/**
 * JBZoo Toolbox - SimpleTypes.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/SimpleTypes
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

final class CompareTest extends PHPUnit
{
    public function testSimple(): void
    {
        isSame(true, val(1)->compare(1, '='));
        isSame(true, val(1)->compare(1, '=='));
        isSame(true, val(1)->compare(1, '==='));
        isSame(true, val(1)->compare(1, '>='));
        isSame(true, val(1)->compare(1, '<='));

        isSame(false, val(1)->compare(1, '<'));
        isSame(false, val(1)->compare(1, '!='));
        isSame(false, val(1)->compare(1, '!=='));
        isSame(false, val(1)->compare(1, '>'));
    }

    public function testComplex(): void
    {
        $v1 = val(1.5);
        $v2 = val('5.6');

        isSame(false, $v1->compare($v2, ' ='));
        isSame(true, $v1->compare($v2, '< '));
        isSame(true, $v1->compare($v2, ' <= '));
        isSame(false, $v1->compare($v2, ' >= '));
        isSame(false, $v1->compare($v2, '>'));
    }

    public function testRules(): void
    {
        $usd = val('1 usd');
        $eur = val('1 eur');

        isSame(false, $usd->compare($eur, '='));
        isSame(true, $usd->compare($eur, '!='));
        isSame(true, $usd->compare($eur, '<'));
        isSame(true, $usd->compare($eur, '<='));
        isSame(false, $usd->compare($eur, '>'));
        isSame(false, $usd->compare($eur, '>='));

        // after convert
        $eur->convert('usd');
        $usd->convert('eur');

        isSame(false, $usd->compare($eur, '=='));
        isSame(true, $usd->compare($eur, '!=='));
        isSame(true, $usd->compare($eur, '<'));
        isSame(true, $usd->compare($eur, '<='));
        isSame(false, $usd->compare($eur, '>'));
        isSame(false, $usd->compare($eur, '>='));
    }

    public function testUndefined(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        val('usd')->compare(0, 'undefined');
    }
}
