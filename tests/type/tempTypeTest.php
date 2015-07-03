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
 * Class tempTypeTest
 * @package SmetDenis\SimpleTypes
 */
class tempTypeTest extends typeTest
{

    protected $_type = 'temp';


    function testCreate()
    {
        $this->_batchEquals(array(
            ['0 k', $this->_('K')->dump(false)],
            ['0 c', $this->_('C')->dump(false)],
            ['0 f', $this->_('F')->dump(false)],
            ['0 r', $this->_('R')->dump(false)],
        ));

    }

    function testConvert()
    {
        $val = $this->_('k');

        $this->_batchEquals(array(
            ['-273.15 c', $val->convert('C')->dump(false)],
            ['-459.67 f', $val->convert('F')->dump(false)],
            ['0 k', $val->convert('K')->dump(false)],
        ));

    }


}