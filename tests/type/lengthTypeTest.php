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
 * Class LengthTypeTest
 * @package SmetDenis\SimpleTypes
 */
class LengthTypeTest extends typeTest
{

    protected $type = 'Length';

    public function testSimple()
    {
        $length = $this->val('1000m');

        // SI
        $this->batchEquals(array(
            array('1000 m', $length->dump(false)),
            array('10000 dm', $length->convert('dm')->dump(false)),
            array('100000 cm', $length->convert('cm')->dump(false)),
            array('1000000 mm', $length->convert('mm')->dump(false)),
        ));

        $this->batchEquals(array(
            array('0.000352777778 m', $this->val('1 p')->convert('m')->dump(false)),
            array('0.2012 m', $this->val('1 li')->convert('m')->dump(false)),
            array('0.0254 m', $this->val('1 in')->convert('m')->dump(false)),
            array('0.3048 m', $this->val('1 ft')->convert('m')->dump(false)),
            array('1609.344 m', $this->val('1 mi')->convert('m')->dump(false)),
        ));
    }
}
