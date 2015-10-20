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

namespace JBZoo\SimpleTypes\Config;

/**
 * class Length
 * @package JBZoo\SimpleTypes
 */
class Length extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'm';
    }

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        return array(
            // SI
            'mm' => array(
                'symbol' => 'mm',
                'rate'   => 0.001,
            ),
            'cm' => array(
                'symbol' => 'cm',
                'rate'   => 0.01,
            ),
            'dm' => array(
                'symbol' => 'dm',
                'rate'   => 0.1,
            ),
            'm'  => array(
                'symbol' => 'm',
                'rate'   => 1,
            ),
            'km' => array(
                'symbol' => 'km',
                'rate'   => 1000,
            ),

            // others
            'p'  => array(
                'symbol' => 'Point',
                'rate'   => 0.000352777778,
            ),
            'li' => array(
                'symbol' => 'Link',
                'rate'   => 0.2012,
            ),
            'in' => array(
                'symbol' => 'Inches',
                'rate'   => 0.0254,
            ),
            'ft' => array(
                'symbol' => 'Foot',
                'rate'   => 0.3048,
            ),
            'yd' => array(
                'symbol' => 'Yard',
                'rate'   => 0.9144,
            ),
            'mi' => array(
                'symbol' => 'Mile',
                'rate'   => 1609.344,
            ),
        );
    }
}
