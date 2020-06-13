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

namespace JBZoo\PHPUnit;

/**
 * Class LengthTypeTest
 * @package JBZoo\SimpleTypes
 */
class LengthTypeTest extends TypeTest
{
    protected $_type = 'Length';

    public function testSimple()
    {
        $length = $this->val('1000m');

        // SI
        isBatch([
            ['1000 m', $length->dump(false)],
            ['10000 dm', $length->convert('dm')->dump(false)],
            ['100000 cm', $length->convert('cm')->dump(false)],
            ['1000000 mm', $length->convert('mm')->dump(false)],
        ]);

        isBatch([
            ['0.000352777778 m', $this->val('1 p')->convert('m')->dump(false)],
            ['0.2012 m', $this->val('1 li')->convert('m')->dump(false)],
            ['0.0254 m', $this->val('1 in')->convert('m')->dump(false)],
            ['0.3048 m', $this->val('1 ft')->convert('m')->dump(false)],
            ['1609.344 m', $this->val('1 mi')->convert('m')->dump(false)],
        ]);
    }
}
