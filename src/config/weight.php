<?php
/**
 * SimpleTypes
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class ConfigWeight
 * @package SmetDenis\SimpleTypes
 */
class ConfigWeight extends Config
{
    public $default = 'g';

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        return array(
            // SI
            'g'         => array(
                'symbol' => 'g',
                'rate'   => 1,
            ),
            'kg'        => array(
                'symbol' => 'Kg',
                'rate'   => 1000,
            ),

            // tons
            'short-ton' => array(
                'symbol' => 'Sh.Tons',
                'rate'   => 907184.74,
            ),
            'ton'       => array(
                'symbol' => 'Tons',
                'rate'   => 1000000,
            ),
            'long-ton'  => array(
                'symbol' => 'Long.Tons',
                'rate'   => 1016046.9088,
            ),

            // Hundredweight
            'short-ct'  => array(
                'symbol' => 'Sh.Centums',
                'rate'   => 45359.237,
            ),
            'ct'        => array(
                'symbol' => 'Centum',
                'rate'   => 100000,
            ),
            'long-ct'   => array(
                'symbol' => 'Long.Centums',
                'rate'   => 50802.345,
            ),

            // other
            'gr'        => array(
                'symbol' => 'Grains',
                'rate'   => 0.06479891,
            ),
            'dr'        => array(
                'symbol' => 'Drams',
                'rate'   => 1.7718451953125,
            ),
            'oz'        => array(
                'symbol' => 'Ounces',
                'rate'   => 28.349523125,
            ),
            'lb'        => array(
                'symbol' => 'Pounds',
                'rate'   => 0.06479891,
            ),
        );

    }
}
