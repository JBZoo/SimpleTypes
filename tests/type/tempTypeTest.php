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
 * Class tempTypeTest
 * @package SmetDenis\SimpleTypes
 */
class TempTypeTest extends typeTest
{

    protected $type = 'temp';

    public function testCreate()
    {
        $this->batchEquals(array(
            ['0 k', $this->val('K')->dump(false)],
            ['0 c', $this->val('C')->dump(false)],
            ['0 f', $this->val('F')->dump(false)],
            ['0 r', $this->val('R')->dump(false)],
        ));
    }

    public function testConvert()
    {
        $val = $this->val('k');

        $this->batchEquals(array(
            ['-273.15 c', $val->convert('C')->dump(false)],
            ['-459.67 f', $val->convert('F')->dump(false)],
            ['0 k', $val->convert('K')->dump(false)],
            ['0 r', $val->convert('R')->dump(false)],
            ['-273.15 c', $val->convert('C')->dump(false)],
            ['0 r', $val->convert('R')->dump(false)],
        ));
    }
}
