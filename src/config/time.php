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
 * Class ConfigTime
 * @package SmetDenis\SimpleTypes
 */
class ConfigTime extends Config
{
    public $default = 's';

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
                'symbol' => 'Month', // Only 30 days!
                'rate'   => 2592000,
            ),
            'q'  => array(
                'symbol' => 'Quarter', // 3 months
                'rate'   => 7776000,
            ),
            'y'  => array(
                'symbol' => 'Year', // 365.25 days
                'rate'   => 31557600,
            ),
        );

    }
}
