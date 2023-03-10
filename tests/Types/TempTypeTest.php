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

final class TempTypeTest extends AbstractTypeTest
{
    protected string $type = 'Temp';

    public function testCreate(): void
    {
        isSame('0 k', $this->val('K')->dump(false));
        isSame('0 c', $this->val('C')->dump(false));
        isSame('0 f', $this->val('F')->dump(false));
        isSame('0 r', $this->val('R')->dump(false));
    }

    public function testConvert(): void
    {
        $val = $this->val('k');

        isSame('-273.15 c', $val->convert('C')->dump(false));
        isSame('-459.67 f', $val->convert('F')->dump(false));
        isSame('0 k', $val->convert('K')->dump(false));
        isSame('0 r', $val->convert('R')->dump(false));
        isSame('-273.15 c', $val->convert('C')->dump(false));
        isSame('0 r', $val->convert('R')->dump(false));
    }
}
