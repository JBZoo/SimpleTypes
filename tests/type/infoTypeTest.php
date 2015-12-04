<?php
/**
 * JBZoo SimpleTypes
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   SimpleTypes
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

/**
 * Class infoTypeTest
 * @package JBZoo\SimpleTypes
 */
class InfoTypeTest extends typeTest
{
    protected $_type = 'Info';

    public function testSimple()
    {
        isBatch(array(
            array('1 KB', $this->val('1024 byte')->text('KB')),
            array('1 KB', $this->val('8192 bit')->text('KB')),
            array('4 GB', $this->val('4294967296 byte')->text('GB')),
            array('32 B', $this->val('256 bit')->text('Byte')),
        ));
    }

    public function testConvert()
    {
        isBatch(array(
            array('81920 bit', $this->val('10Kb')->convert('bit')->dump(false)),
            array('10 kb', $this->val('81920bit')->convert('mb')->convert('kb')->dump(false)),
        ));
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
