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
 * Class AreaTypeTest
 * @package JBZoo\SimpleTypes
 */
class AreaTypeTest extends TypeTest
{
    protected $_type = 'Area';

    public function testSimple()
    {
        $length = $this->val('1000m2');

        // SI
        isBatch([
            ['1000 m2', $length->dump(false)],
            ['1000000000 mm2', $length->convert('mm2')->dump(false)],
            ['10000000 cm2', $length->convert('cm2')->dump(false)],
            ['0.001 km2', $length->convert('km2')->dump(false)],
            ['10 ar', $length->convert('ar')->dump(false)],
            ['0.1 ga', $length->convert('ga')->dump(false)],
        ]);

        isBatch([
            ['0.09290341 m2', $this->val('1 ft2')->convert('m2')->dump(false)],
            ['404.6873 m2', $this->val('1 ch2')->convert('m2')->dump(false)],
            ['4046.873 m2', $this->val('1 acr')->convert('m2')->dump(false)],
        ]);
    }
}
