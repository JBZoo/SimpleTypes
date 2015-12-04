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

namespace JBZoo\SimpleTypes\Config;

/**
 * class Degree
 * @package JBZoo\SimpleTypes
 */
class Degree extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'd';
    }

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        return array(
            // degree
            'd' => array(
                'format_positive' => '%v%s',
                'format_negative' => '-%v%s',
                'symbol'          => '°',
            ),

            // radian
            'r' => array(
                'symbol' => 'pi',
                'rate'   => function ($value, $ruleTo) {
                    if ($ruleTo === 'd') {
                        return $value * 180;
                    }
                    return $value / 180;
                },
            ),

            // grads
            'g' => array(
                'symbol' => 'Grad',
                'rate'   => function ($value, $ruleTo) {
                    if ($ruleTo === 'd') {
                        return $value * 0.9;
                    }
                    return $value / 0.9;
                },
            ),

            // turn (loop)
            't' => array(
                'symbol' => 'Turn',
                'rate'   => 360,
            ),
        );
    }
}
