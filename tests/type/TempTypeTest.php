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
 * Class tempTypeTest
 * @package JBZoo\SimpleTypes
 */
class TempTypeTest extends TypeTest
{
    protected $_type = 'Temp';

    public function testCreate()
    {
        isBatch([
            ['0 k', $this->val('K')->dump(false)],
            ['0 c', $this->val('C')->dump(false)],
            ['0 f', $this->val('F')->dump(false)],
            ['0 r', $this->val('R')->dump(false)],
        ]);
    }

    public function testConvert()
    {
        $val = $this->val('k');

        isBatch([
            ['-273.15 c', $val->convert('C')->dump(false)],
            ['-459.67 f', $val->convert('F')->dump(false)],
            ['0 k', $val->convert('K')->dump(false)],
            ['0 r', $val->convert('R')->dump(false)],
            ['-273.15 c', $val->convert('C')->dump(false)],
            ['0 r', $val->convert('R')->dump(false)],
        ]);
    }
}
