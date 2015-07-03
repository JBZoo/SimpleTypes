<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package    SimpleTypes
 * @author     Denis Smetannikov <denis@jbzoo.com>
 * @copyright  2015 Denis Smetannikov <denis@jbzoo.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link       http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;


/**
 * Class infoTypeTest
 * @package SmetDenis\SimpleTypes
 */
class infoTypeTest extends typeTest
{

    protected $_type = 'info';

    function testSimple()
    {
        $this->_batchEquals(array(
            ['1 KB', $this->_('1024 byte')->text('KB')],
            ['1 KB', $this->_('8192 bit')->text('KB')],
            ['4 GB', $this->_('4294967296 byte')->text('GB')],
            ['32 B', $this->_('256 bit')->text('Byte')],
        ));
    }

    function testConvert()
    {
        $this->_batchEquals(array(
            ['81920 bit', $this->_('10Kb')->convert('bit')->dump(false)],
            ['10 kb', $this->_('81920bit')->convert('mb')->convert('kb')->dump(false)]
        ));
    }

    function testCompare()
    {
        $this->assertTrue($this->_('10kb')->compare('81920bit'));
    }

    function testComplex()
    {
        $val = $this->_('81920 bit')
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