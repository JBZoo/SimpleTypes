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
 * Class ConfigWeight
 * @package SmetDenis\SimpleTypes
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
