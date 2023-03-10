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

final class TimeTypeTest extends AbstractTypeTest
{
    protected string $type = 'Time';

    public function testSimple(): void
    {
        $time = $this->val(60 * 60 * 24 * 30);

        isSame('2592000 s', $time->dump(false));
        isSame('43200 m', $time->convert('m')->dump(false));
        isSame('720 h', $time->convert('h')->dump(false));
        isSame('30 d', $time->convert('d')->dump(false));
        isSame('1 mo', $time->convert('mo')->dump(false));
        isSame((30 / 7) . ' w', $time->convert('w')->dump(false));
        isSame((1 / 3) . ' q', $time->convert('q')->dump(false));
        isSame((30 / 365.25) . ' y', $time->convert('y')->dump(false));
    }
}
