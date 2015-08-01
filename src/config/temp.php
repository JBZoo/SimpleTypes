<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class ConfigTemp
 * @package SmetDenis\SimpleTypes
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
