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
 * Class TimeTypeTest
 * @package SmetDenis\SimpleTypes
 */
class TimeTypeTest extends TypeTest
{

    protected $type = 'time';

    public function testSimple()
    {
        $time = $this->val(60 * 60 * 24 * 30);

        $this->batchEquals(array(
            array('2592000 s', $time->dump(false)),
            array('43200 m', $time->convert('m')->dump(false)),
            array('720 h', $time->convert('h')->dump(false)),
            array('30 d', $time->convert('d')->dump(false)),
            array('1 mo', $time->convert('mo')->dump(false)),
            array((30 / 7) . ' w', $time->convert('w')->dump(false)),
            array((1 / 3) . ' q', $time->convert('q')->dump(false)),
            array((30 / 365.25) . ' y', $time->convert('y')->dump(false)),
        ));
    }
}
