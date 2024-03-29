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

final class WeightTypeTest extends AbstractTypeTest
{
    protected string $type = 'Weight';

    public function testSimple(): void
    {
        $weight = $this->val('10000g');

        // SI
        isSame('10000 g', $weight->dump(false));
        isSame('10 kg', $weight->convert('kg')->dump(false));
        isSame('0.01 ton', $weight->convert('ton')->dump(false));

        isSame('0.06479891 g', $this->val('1 gr')->convert('g')->dump(false));
        isSame('1.7718451953125 g', $this->val('1 dr')->convert('g')->dump(false));
        isSame('28.349523125 g', $this->val('1 oz')->convert('g')->dump(false));
        isSame('453.59237 g', $this->val('1 lb')->convert('g')->dump(false));
    }
}
