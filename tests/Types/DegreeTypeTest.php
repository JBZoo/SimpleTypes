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

namespace JBZoo\PHPUnit\Types;

use function JBZoo\PHPUnit\is;
use function JBZoo\PHPUnit\isSame;

final class DegreeTypeTest extends AbstractTypeTest
{
    protected string $type = 'Degree';

    public function testSimple(): void
    {
        $val = $this->val('180 d');

        isSame('180 d', $val->dump(false));
        isSame('1 r', $val->convert('r')->dump(false));
        isSame('200 g', $val->convert('g')->dump(false));
        isSame('0.5 t', $val->convert('t')->dump(false));
    }

    public function testMoreThan360(): void
    {
        $val = $this->val('1.5 r');

        isSame('270 d', $val->convert('d')->dump(false));
        isSame('1.5 r', $val->convert('r')->dump(false));
        isSame('300 g', $val->convert('g')->dump(false));
        isSame('0.75 t', $val->convert('t')->dump(false));
    }

    public function testLessThan0(): void
    {
        $val = $this->val('-1 r');

        isSame('-180 d', $val->convert('d')->dump(false));
        isSame('-1 r', $val->convert('r')->dump(false));
        isSame('-200 g', $val->convert('g')->dump(false));
        isSame('-0.5 t', $val->convert('t')->dump(false));
    }

    public function testRemoveCirclesDeg(): void
    {
        is('180 d', $this->val('540 d')->removeCircles()->dump(false));
        is('-1 r', $this->val('-5 r')->removeCircles()->dump(false));
        is('0 g', $this->val('1600 g')->removeCircles()->dump(false));
        is('-0.55 t', $this->val('-5.55 t')->removeCircles()->dump(false));
    }
}
