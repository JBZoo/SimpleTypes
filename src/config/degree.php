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
 * Class ConfigDegree
 * @package SmetDenis\SimpleTypes
 */
class ConfigDegree extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'd';
    }

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        return array(
            // degree
            'd' => array(
                'format_positive' => '%v%s',
                'format_negative' => '-%v%s',
                'symbol'          => '°',
            ),
            // radian
            'r' => array(
                'symbol' => 'pi',
                'rate'   => function ($value, $to) {
                    if ($to === 'd') {
                        return $value * 180;
                    }
                    return $value / 180;
                },
            ),
            // grads
            'g' => array(
                'symbol' => 'Grad',
                'rate'   => function ($value, $to) {
                    if ($to === 'd') {
                        return $value * 0.9;
                    }
                    return $value / 0.9;
                },
            ),
            // turn (loop)
            't' => array(
                'symbol' => 'Turn',
                'rate'   => 360,
            ),
        );
    }
}
