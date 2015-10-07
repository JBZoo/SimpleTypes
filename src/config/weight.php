<?php
/**
 * SimpleTypes
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
 * Class ConfigWeight
 * @package JBZoo\SimpleTypes
 */
class ConfigWeight extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'g';
    }

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        return array(
            // SI
            'g'     => array(
                'symbol' => 'g',
                'rate'   => 1,
            ),
            'kg'    => array(
                'symbol' => 'Kg',
                'rate'   => 1000,
            ),
            'ton'   => array(
                'symbol' => 'Tons',
                'rate'   => 1000000,
            ),

            // other
            'gr'    => array(
                'symbol' => 'Grains',
                'rate'   => 0.06479891,
            ),
            'dr'    => array(
                'symbol' => 'Drams',
                'rate'   => 1.7718451953125,
            ),
            'oz'    => array(
                'symbol' => 'Ounces',
                'rate'   => 28.349523125,
            ),
            'lb'    => array(
                'symbol' => 'Pounds',
                'rate'   => 453.59237,
            ),
        );

    }
}
