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
 * Class ConfigVolume
 * @package SmetDenis\SimpleTypes
 */
class ConfigVolume extends Config
{
    public $default = 'l';

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        return array(
            // SI
            'ml'      => array(
                'symbol' => 'mL',
                'rate'   => 0.001,
            ),
            'cm3'     => array(
                'symbol' => 'cm3',
                'rate'   => 0.1,
            ),
            'l'       => array(
                'symbol' => 'L',
                'rate'   => 1,
            ),
            'm3'      => array(
                'symbol' => 'm3',
                'rate'   => 1000,
            ),

            // other
            'quart'   => array(
                'symbol' => 'Quart',
                'rate'   => 1.1365225,
            ),
            'f-quart' => array(
                'symbol' => 'Fluid Quart',
                'rate'   => 0.946352946,
            ),
            'pint'    => array(
                'symbol' => 'Pint',
                'rate'   => 0.56826125,
            ),
            'f-pint'  => array(
                'symbol' => 'Fluid Pint',
                'rate'   => 0.473176473,
            ),
            'gal'     => array(
                'symbol' => 'Gallon',
                'rate'   => 4.54609,
            ),
            'l-gal'   => array(
                'symbol' => 'L.Gallon',
                'rate'   => 3.785411784,
            ),
            'ft3'     => array(
                'symbol' => 'Cubic ft.',
                'rate'   => 28.316846592,
            ),
            'in3'     => array(
                'symbol' => 'Cubic in.',
                'rate'   => 0.016387064,
            ),
            'f-ounce' => array(
                'symbol' => 'Fluid oz.',
                'rate'   => 28.4130625,
            ),
        );
    }
}
