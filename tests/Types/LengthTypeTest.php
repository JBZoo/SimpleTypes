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

use function JBZoo\PHPUnit\isSame;

final class LengthTypeTest extends AbstractTypeTest
{
    protected string $type = 'Length';

    public function testSimple(): void
    {
        $length = $this->val('1000m');

        // SI
        isSame('1000 m', $length->dump(false));
        isSame('10000 dm', $length->convert('dm')->dump(false));
        isSame('100000 cm', $length->convert('cm')->dump(false));
        isSame('1000000 mm', $length->convert('mm')->dump(false));

        isSame('0.000352777778 m', $this->val('1 p')->convert('m')->dump(false));
        isSame('0.2012 m', $this->val('1 li')->convert('m')->dump(false));
        isSame('0.0254 m', $this->val('1 in')->convert('m')->dump(false));
        isSame('0.3048 m', $this->val('1 ft')->convert('m')->dump(false));
        isSame('1609.344 m', $this->val('1 mi')->convert('m')->dump(false));
    }
}
