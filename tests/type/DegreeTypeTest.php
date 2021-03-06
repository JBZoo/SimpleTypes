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
 * Class DegreeTypeTest
 * @package JBZoo\SimpleTypes
 */
class DegreeTypeTest extends TypeTest
{
    protected $_type = 'Degree';

    public function testSimple()
    {
        $val = $this->val('180 d');

        isBatch([
            ['180 d', $val->dump(false)],
            ['1 r', $val->convert('r')->dump(false)],
            ['200 g', $val->convert('g')->dump(false)],
            ['0.5 t', $val->convert('t')->dump(false)],
        ]);
    }

    public function testMoreThan360()
    {
        $val = $this->val('1.5 r');

        isBatch([
            ['270 d', $val->convert('d')->dump(false)],
            ['1.5 r', $val->convert('r')->dump(false)],
            ['300 g', $val->convert('g')->dump(false)],
            ['0.75 t', $val->convert('t')->dump(false)],
        ]);
    }

    public function testLessThan0()
    {
        $val = $this->val('-1 r');

        isBatch([
            ['-180 d', $val->convert('d')->dump(false)],
            ['-1 r', $val->convert('r')->dump(false)],
            ['-200 g', $val->convert('g')->dump(false)],
            ['-0.5 t', $val->convert('t')->dump(false)],
        ]);
    }

    public function testRemoveCirclesDeg()
    {
        is('180 d', $this->val('540 d')->removeCircles()->dump(false));
        is('-1 r', $this->val('-5 r')->removeCircles()->dump(false));
        is('0 g', $this->val('1600 g')->removeCircles()->dump(false));
        is('-0.55 t', $this->val('-5.55 t')->removeCircles()->dump(false));
    }
}
