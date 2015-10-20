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
 * Class ConfigVolume
 * @package JBZoo\SimpleTypes
 */
class ConfigVolume extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'lit';
    }

    /**
     * List of rules
     * @link https://en.wikipedia.org/wiki/United_States_customary_units
     * @return array
     */
    public function getRules()
    {
        return array(
            // SI
            'ml'  => array(
                'symbol' => 'mL',
                'rate'   => 0.001,
            ),
            'cm3' => array(
                'symbol' => 'cm3',
                'rate'   => 0.1,
            ),
            'm3'  => array(
                'symbol' => 'm3',
                'rate'   => 1000,
            ),
            'lit' => array(
                'symbol' => 'L',
                'rate'   => 1,
            ),
            // other
            'qt'  => array(
                'symbol' => 'US quart',
                'rate'   => 0.946352946,
            ),
            'pt'  => array(
                'symbol' => 'US pint',
                'rate'   => 0.56826125,
            ),
            'gal' => array(
                'symbol' => 'US gallon',
                'rate'   => 3.785411784,
            ),
            'bbl' => array(
                'symbol' => 'Barrel',
                'rate'   => 119.240471196,
            ),
        );
    }
}
