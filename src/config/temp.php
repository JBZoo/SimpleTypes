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
 * Class ConfigTemp
 * @package JBZoo\SimpleTypes
 */
class ConfigTemp extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'k';
    }

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        $this->defaultParams ['format_positive'] = '%v%s';
        $this->defaultParams ['format_negative'] = '-%v%s';

        return array(

            // Celsius
            'C' => array(
                'symbol' => '°C',
                'rate'   => function ($value, $ruleTo) {

                    if ($ruleTo === 'k') {
                        $value += 273.15;
                    } else {
                        $value -= 273.15;
                    }

                    return $value;
                },
            ),

            // Fahrenheit
            'F' => array(
                'symbol' => '°F',
                'rate'   => function ($value, $ruleTo) {

                    if ($ruleTo === 'k') {
                        $value = ($value + 459.67) * (5 / 9);
                    } else {
                        $value = $value * (9 / 5) - 459.67;
                    }

                    return $value;
                },
            ),

            // Rankine
            'R' => array(
                'symbol' => '°R',
                'rate'   => function ($value, $ruleTo) {

                    if ($ruleTo === 'k') {
                        $value = $value * 5 / 9;
                    } else {
                        $value = $value * 9 / 5;
                    }

                    return $value;
                },
            ),

            // Kelvin
            'K' => array(
                'symbol' => 'K',
                'rate'   => function ($value) {
                    return $value;
                },
            ),
        );
    }
}
