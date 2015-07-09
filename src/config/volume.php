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
    public $default = 'lit';

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
