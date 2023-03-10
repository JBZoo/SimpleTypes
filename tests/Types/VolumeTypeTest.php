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

final class VolumeTypeTest extends AbstractTypeTest
{
    protected string $type = 'Volume';

    public function testSimple(): void
    {
        // SI
        $vol = $this->val('1 m3');

        isSame('1 m3', $vol->dump(false));
        isSame('1000000 ml', $vol->convert('ml')->dump(false));
        isSame('10000 cm3', $vol->convert('cm3')->dump(false));
        isSame('1000 lit', $vol->convert('lit')->dump(false));

        // other
        isSame('0.946352946 lit', $this->val('1qt')->convert('lit')->dump(false));
        isSame('0.56826125 lit', $this->val('1pt')->convert('lit')->dump(false));
        isSame('3.785411784 lit', $this->val('1gal')->convert('lit')->dump(false));
        isSame('119.240471196 lit', $this->val('1bbl')->convert('lit')->dump(false));
    }
}
