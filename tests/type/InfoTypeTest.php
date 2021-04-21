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
 * Class infoTypeTest
 * @package JBZoo\SimpleTypes
 */
class InfoTypeTest extends TypeTest
{
    protected $_type = 'Info';

    public function testSimple()
    {
        isBatch([
            ['1 KB', $this->val('1024 byte')->text('KB')],
            ['1 KB', $this->val('8192 bit')->text('KB')],
            ['4 GB', $this->val('4294967296 byte')->text('GB')],
            ['32 B', $this->val('256 bit')->text('Byte')],
        ]);
    }

    public function testConvert()
    {
        isBatch([
            ['81920 bit', $this->val('10Kb')->convert('bit')->dump(false)],
            ['10 kb', $this->val('81920bit')->convert('mb')->convert('kb')->dump(false)],
        ]);
    }

    public function testCompare()
    {
        isTrue($this->val('10kb')->compare('81920bit'));
    }

    public function testComplex()
    {
        $val = $this->val('81920 bit')
            ->subtract('9 KB')
            ->multiply(5)
            ->convert('KB')
            ->convert('bit')
            ->multiply(1024)
            ->add('512 kb')
            ->convert('mb');

        is('5.5 mb', $val->dump(false));
    }
}
