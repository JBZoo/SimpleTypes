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
 * Class VolumeTypeTest
 * @package JBZoo\SimpleTypes
 */
class VolumeTypeTest extends TypeTest
{
    protected $_type = 'Volume';

    public function testSimple()
    {
        // SI
        $vol = $this->val('1 m3');

        isBatch(array(
            array('1 m3', $vol->dump(false)),
            array('1000000 ml', $vol->convert('ml')->dump(false)),
            array('10000 cm3', $vol->convert('cm3')->dump(false)),
            array('1000 lit', $vol->convert('lit')->dump(false)),
        ));

        // other
        isBatch(array(
            array('0.946352946 lit', $this->val('1qt')->convert('lit')->dump(false)),
            array('0.56826125 lit', $this->val('1pt')->convert('lit')->dump(false)),
            array('3.785411784 lit', $this->val('1gal')->convert('lit')->dump(false)),
            array('119.240471196 lit', $this->val('1bbl')->convert('lit')->dump(false)),
        ));
    }
}
