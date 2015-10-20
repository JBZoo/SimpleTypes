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

namespace JBZoo\SimpleTypes;

/**
 * Class tempTypeTest
 * @package JBZoo\SimpleTypes
 */
class TempTypeTest extends typeTest
{

    protected $type = 'Temp';

    public function testCreate()
    {
        $this->batchEquals(array(
            array('0 k', $this->val('K')->dump(false)),
            array('0 c', $this->val('C')->dump(false)),
            array('0 f', $this->val('F')->dump(false)),
            array('0 r', $this->val('R')->dump(false)),
        ));
    }

    public function testConvert()
    {
        $val = $this->val('k');

        $this->batchEquals(array(
            array('-273.15 c', $val->convert('C')->dump(false)),
            array('-459.67 f', $val->convert('F')->dump(false)),
            array('0 k', $val->convert('K')->dump(false)),
            array('0 r', $val->convert('R')->dump(false)),
            array('-273.15 c', $val->convert('C')->dump(false)),
            array('0 r', $val->convert('R')->dump(false)),
        ));
    }
}
