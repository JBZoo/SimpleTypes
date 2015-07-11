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
 * Class AreaTypeTest
 * @package SmetDenis\SimpleTypes
 */
class AreaTypeTest extends typeTest
{

    protected $type = 'area';

    public function testSimple()
    {
        $length = $this->val('1000m2');

        // SI
        $this->batchEquals(array(
            array('1000 m2', $length->dump(false)),
            array('1000000000 mm2', $length->convert('mm2')->dump(false)),
            array('10000000 cm2', $length->convert('cm2')->dump(false)),
            array('0.001 km2', $length->convert('km2')->dump(false)),
            array('10 ar', $length->convert('ar')->dump(false)),
            array('0.1 ga', $length->convert('ga')->dump(false)),
        ));

        $this->batchEquals(array(
            array('0.09290341 m2', $this->val('1 ft2')->convert('m2')->dump(false)),
            array('404.6873 m2', $this->val('1 ch2')->convert('m2')->dump(false)),
            array('4046.873 m2', $this->val('1 acr')->convert('m2')->dump(false)),
        ));
    }
}