<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class infoTypeTest
 * @package SmetDenis\SimpleTypes
 */
class infoTypeTest extends typeTest
{

    protected $type = 'info';

    public function testSimple()
    {
        $this->batchEquals(array(
            ['1 KB', $this->val('1024 byte')->text('KB')],
            ['1 KB', $this->val('8192 bit')->text('KB')],
            ['4 GB', $this->val('4294967296 byte')->text('GB')],
            ['32 B', $this->val('256 bit')->text('Byte')],
        ));
    }

    public function testConvert()
    {
        $this->batchEquals(array(
            ['81920 bit', $this->val('10Kb')->convert('bit')->dump(false)],
            ['10 kb', $this->val('81920bit')->convert('mb')->convert('kb')->dump(false)]
        ));
    }

    public function testCompare()
    {
        $this->assertTrue($this->val('10kb')->compare('81920bit'));
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

        $this->assertEquals('5.5 mb', $val->dump(false));
    }
}
