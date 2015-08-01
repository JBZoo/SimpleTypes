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
 * Class ConfigMoney
 * @package SmetDenis\SimpleTypes
 */
class ConfigInfo extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'byte';
    }

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        $base = 1024;

        $this->defaultParams['num_decimals'] = 0;
        $this->defaultParams['round_type']   = Formatter::ROUND_NONE;

        return array(
            'byte' => array(
                'symbol' => 'B',
                'rate'   => 1,
            ),
            'kb'   => array(
                'symbol' => 'KB',
                'rate'   => pow($base, 1),
            ),
            'mb'   => array(
                'symbol' => 'MB',
                'rate'   => pow($base, 2),
            ),
            'gb'   => array(
                'symbol' => 'GB',
                'rate'   => pow($base, 3),
            ),
            'tb'   => array(
                'symbol' => 'TB',
                'rate'   => pow($base, 4),
            ),
            'pb'   => array(
                'symbol' => 'PB',
                'rate'   => pow($base, 5),
            ),
            'eb'   => array(
                'symbol' => 'EB',
                'rate'   => pow($base, 6),
            ),
            'zb'   => array(
                'symbol' => 'ZB',
                'rate'   => pow($base, 7),
            ),
            'yb'   => array(
                'symbol' => 'YB',
                'rate'   => pow($base, 8),
            ),
            'bit'  => array(
                'symbol' => 'Bit',
                'rate'   => function ($value, $ruleTo) {

                    if ($ruleTo === 'bit') {
                        return $value * 8;
                    }

                    return $value / 8;
                },
            ),
        );
    }
}
