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
 * Class WeightTypeTest
 * @package JBZoo\SimpleTypes
 */
class WeightTypeTest extends TypeTest
{
    protected $_type = 'Weight';

    public function testSimple()
    {
        $weight = $this->val('10000g');

        // SI
        isBatch([
            ['10000 g', $weight->dump(false)],
            ['10 kg', $weight->convert('kg')->dump(false)],
            ['0.01 ton', $weight->convert('ton')->dump(false)],
        ]);

        isBatch([
            ['0.06479891 g', $this->val('1 gr')->convert('g')->dump(false)],
            ['1.7718451953125 g', $this->val('1 dr')->convert('g')->dump(false)],
            ['28.349523125 g', $this->val('1 oz')->convert('g')->dump(false)],
            ['453.59237 g', $this->val('1 lb')->convert('g')->dump(false)],
        ]);
    }
}
