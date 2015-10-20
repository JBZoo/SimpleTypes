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
 * Class TimeTypeTest
 * @package JBZoo\SimpleTypes
 */
class TimeTypeTest extends TypeTest
{

    protected $type = 'Time';

    public function testSimple()
    {
        $time = $this->val(60 * 60 * 24 * 30);

        isBatch(array(
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
