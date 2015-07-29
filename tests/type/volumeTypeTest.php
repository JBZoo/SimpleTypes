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
 * Class VolumeTypeTest
 * @package SmetDenis\SimpleTypes
 */
class VolumeTypeTest extends TypeTest
{

    protected $type = 'volume';

    public function testSimple()
    {
        // SI
        $vol = $this->val('1 m3');
        $this->batchEquals(array(
            array('1 m3', $vol->dump(false)),
            array('1000000 ml', $vol->convert('ml')->dump(false)),
            array('10000 cm3', $vol->convert('cm3')->dump(false)),
            array('1000 lit', $vol->convert('lit')->dump(false)),
        ));

        // other
        $this->batchEquals(array(
            array('0.946352946 lit', $this->val('1qt')->convert('lit')->dump(false)),
            array('0.56826125 lit', $this->val('1pt')->convert('lit')->dump(false)),
            array('3.785411784 lit', $this->val('1gal')->convert('lit')->dump(false)),
            array('119.240471196 lit', $this->val('1bbl')->convert('lit')->dump(false)),
        ));
    }
}
