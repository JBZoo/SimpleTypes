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
 * Class DegreeTypeTest
 * @package JBZoo\SimpleTypes
 */
class DegreeTypeTest extends TypeTest
{

    protected $type = 'Degree';

    public function testSimple()
    {
        $val = $this->val('180 d');

        isBatch(array(
            array('180 d', $val->dump(false)),
            array('1 r', $val->convert('r')->dump(false)),
            array('200 g', $val->convert('g')->dump(false)),
            array('0.5 t', $val->convert('t')->dump(false)),
        ));
    }

    public function testMoreThan360()
    {
        $val = $this->val('1.5 r');

        isBatch(array(
            array('270 d', $val->convert('d')->dump(false)),
            array('1.5 r', $val->convert('r')->dump(false)),
            array('300 g', $val->convert('g')->dump(false)),
            array('0.75 t', $val->convert('t')->dump(false)),
        ));
    }

    public function testLessThan0()
    {
        $val = $this->val('-1 r');

        isBatch(array(
            array('-180 d', $val->convert('d')->dump(false)),
            array('-1 r', $val->convert('r')->dump(false)),
            array('-200 g', $val->convert('g')->dump(false)),
            array('-0.5 t', $val->convert('t')->dump(false)),
        ));
    }

    public function testRemoveCirclesDeg()
    {
        is('180 d', $this->val('540 d')->removeCircles()->dump(false));
        is('-1 r', $this->val('-5 r')->removeCircles()->dump(false));
        is('0 g', $this->val('1600 g')->removeCircles()->dump(false));
        is('-0.55 t', $this->val('-5.55 t')->removeCircles()->dump(false));
    }
}
