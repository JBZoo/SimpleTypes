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
 * class Area
 * @package JBZoo\SimpleTypes
 */
class Area extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'm2';
    }

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        return array(
            // SI
            'mm2' => array(
                'symbol' => 'mm2',
                'rate'   => 0.000001,
            ),
            'cm2' => array(
                'symbol' => 'cm2',
                'rate'   => 0.0001,
            ),
            'm2'  => array(
                'symbol' => 'm2',
                'rate'   => 1,
            ),
            'km2' => array(
                'symbol' => 'km2',
                'rate'   => 1000000,
            ),

            // other
            'ft2' => array(
                'symbol' => 'sq ft',
                'rate'   => 0.09290341,
            ),
            'ch2' => array(
                'symbol' => 'sq ch',
                'rate'   => 404.6873,
            ),
            'acr' => array(
                'symbol' => 'Acre',
                'rate'   => 4046.873,
            ),
            'ar'  => array(
                'symbol' => 'Ar', // "Sotka"
                'rate'   => 100,
            ),
            'ga'  => array(
                'symbol' => 'Ga',
                'rate'   => 10000,
            ),
        );
    }
}
