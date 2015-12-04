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
 * Class LengthTypeTest
 * @package JBZoo\SimpleTypes
 */
class LengthTypeTest extends typeTest
{
    protected $_type = 'Length';

    public function testSimple()
    {
        $length = $this->val('1000m');

        // SI
        isBatch(array(
            array('1000 m', $length->dump(false)),
            array('10000 dm', $length->convert('dm')->dump(false)),
            array('100000 cm', $length->convert('cm')->dump(false)),
            array('1000000 mm', $length->convert('mm')->dump(false)),
        ));

        isBatch(array(
            array('0.000352777778 m', $this->val('1 p')->convert('m')->dump(false)),
            array('0.2012 m', $this->val('1 li')->convert('m')->dump(false)),
            array('0.0254 m', $this->val('1 in')->convert('m')->dump(false)),
            array('0.3048 m', $this->val('1 ft')->convert('m')->dump(false)),
            array('1609.344 m', $this->val('1 mi')->convert('m')->dump(false)),
        ));
    }
}
