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
 * class Time
 * @package JBZoo\SimpleTypes
 */
class Time extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 's';
    }

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        return array(
            's'  => array(
                'symbol' => 'Sec',
                'rate'   => 1,
            ),
            'm'  => array(
                'symbol' => 'Min',
                'rate'   => 60,
            ),
            'h'  => array(
                'symbol' => 'H',
                'rate'   => 3600,
            ),
            'd'  => array(
                'symbol' => 'Day',
                'rate'   => 86400,
            ),
            'w'  => array(
                'symbol' => 'Week',
                'rate'   => 604800,
            ),
            'mo' => array(
                'symbol' => 'Month',    // Only 30 days!
                'rate'   => 2592000,
            ),
            'q'  => array(
                'symbol' => 'Quarter',  // 3 months
                'rate'   => 7776000,
            ),
            'y'  => array(
                'symbol' => 'Year',     // 365.25 days
                'rate'   => 31557600,
            ),
        );

    }
}
