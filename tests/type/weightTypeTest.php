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
 * Class WeightTypeTest
 * @package JBZoo\SimpleTypes
 */
class WeightTypeTest extends typeTest
{

    protected $_type = 'Weight';

    public function testSimple()
    {
        $weight = $this->val('10000g');

        // SI
        isBatch(array(
            array('10000 g', $weight->dump(false)),
            array('10 kg', $weight->convert('kg')->dump(false)),
            array('0.01 ton', $weight->convert('ton')->dump(false)),
        ));

        isBatch(array(
            array('0.06479891 g', $this->val('1 gr')->convert('g')->dump(false)),
            array('1.7718451953125 g', $this->val('1 dr')->convert('g')->dump(false)),
            array('28.349523125 g', $this->val('1 oz')->convert('g')->dump(false)),
            array('453.59237 g', $this->val('1 lb')->convert('g')->dump(false)),
        ));
    }
}
