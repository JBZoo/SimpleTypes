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
 * Class ConfigLength
 * @package SmetDenis\SimpleTypes
 */
class ConfigLength extends Config
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
