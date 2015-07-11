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
 * Class WeightTypeTest
 * @package SmetDenis\SimpleTypes
 */
class WeightTypeTest extends typeTest
{

    protected $type = 'weight';

    public function testSimple()
    {
        $weight = $this->val('10000g');

        // SI
        $this->batchEquals(array(
            array('10000 g', $weight->dump(false)),
            array('10 kg', $weight->convert('kg')->dump(false)),
            array('0.01 ton', $weight->convert('ton')->dump(false)),
        ));

        $this->batchEquals(array(
            array('0.06479891 g', $this->val('1 gr')->convert('g')->dump(false)),
            array('1.7718451953125 g', $this->val('1 dr')->convert('g')->dump(false)),
            array('28.349523125 g', $this->val('1 oz')->convert('g')->dump(false)),
            array('453.59237 g', $this->val('1 lb')->convert('g')->dump(false)),
        ));
    }
}
