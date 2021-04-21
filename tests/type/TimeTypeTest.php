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
 * Class TimeTypeTest
 * @package JBZoo\SimpleTypes
 */
class TimeTypeTest extends TypeTest
{
    protected $_type = 'Time';

    public function testSimple()
    {
        $time = $this->val(60 * 60 * 24 * 30);

        isBatch([
            ['2592000 s', $time->dump(false)],
            ['43200 m', $time->convert('m')->dump(false)],
            ['720 h', $time->convert('h')->dump(false)],
            ['30 d', $time->convert('d')->dump(false)],
            ['1 mo', $time->convert('mo')->dump(false)],
            [(30 / 7) . ' w', $time->convert('w')->dump(false)],
            [(1 / 3) . ' q', $time->convert('q')->dump(false)],
            [(30 / 365.25) . ' y', $time->convert('y')->dump(false)],
        ]);
    }
}
