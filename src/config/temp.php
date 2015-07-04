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
    public $default = 'k';
    public $isDebug = true;

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        return array(

            // Celsius
            'C' => array_merge($this->defaultParams, array(
                'symbol' => '°C',
                'rate'   => function ($value, $to) {

                    if ($to == 'k') {
                        $value = $value + 273.15;
                    } else {
                        $value = $value - 273.15;
                    }

                    return $value;
                },
            )),

            // Fahrenheit
            'F' => array_merge($this->defaultParams, array(
                'symbol' => '°F',
                'rate'   => function ($value, $to) {

                    if ($to == 'k') {
                        $value = ($value + 459.67) * (5 / 9);
                    } else {
                        $value = $value * (9 / 5) - 459.67;
                    }

                    return $value;
                },
            )),

            // Rankine
            'R' => array_merge($this->defaultParams, array(
                'symbol' => '°R',
                'rate'   => function ($value, $to) {

                    if ($to == 'k') {
                        $value = $value * 5 / 9;
                    } else {
                        $value = $value * 9 / 5;
                    }

                    return $value;
                },
            )),

            // Kelvin
            'K' => array_merge($this->defaultParams, array(
                'symbol' => 'K',
                'rate'   => function ($value) {
                    return $value;
                },
            )),
        );
    }
}
