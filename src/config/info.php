<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package    SimpleTypes
 * @author     Denis Smetannikov <denis@jbzoo.com>
 * @copyright  2015 Denis Smetannikov <denis@jbzoo.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link       http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;


/**
 * Class ConfigMoney
 * @package SmetDenis\SimpleTypes
 */
class ConfigInfo extends Config
{
    public $default = 'byte';
    public $isDebug = true;

    public function getRules()
    {
        $base = 1024;

        $this->_defaultParams['num_decimals'] = 0;
        $this->_defaultParams['round_type']   = Formatter::ROUND_NONE;

        return array(
            'byte' => array_merge($this->_defaultParams, array(
                'symbol' => 'B',
                'rate'   => 1,
            )),
            'kb'   => array_merge($this->_defaultParams, array(
                'symbol' => 'KB',
                'rate'   => pow($base, 1),
            )),
            'mb'   => array_merge($this->_defaultParams, array(
                'symbol' => 'MB',
                'rate'   => pow($base, 2),
            )),
            'gb'   => array_merge($this->_defaultParams, array(
                'symbol' => 'GB',
                'rate'   => pow($base, 3),
            )),
            'tb'   => array_merge($this->_defaultParams, array(
                'symbol' => 'TB',
                'rate'   => pow($base, 4),
            )),
            'pb'   => array_merge($this->_defaultParams, array(
                'symbol' => 'PB',
                'rate'   => pow($base, 5),
            )),
            'eb'   => array_merge($this->_defaultParams, array(
                'symbol' => 'EB',
                'rate'   => pow($base, 6),
            )),
            'zb'   => array_merge($this->_defaultParams, array(
                'symbol' => 'ZB',
                'rate'   => pow($base, 7),
            )),
            'yb'   => array_merge($this->_defaultParams, array(
                'symbol' => 'YB',
                'rate'   => pow($base, 8),
            )),

            'bit'  => array_merge($this->_defaultParams, array(
                'symbol' => 'Bit',
                'rate'   => function ($value, $to) {

                    if ($to == 'bit') {
                        $value = $value * 8;
                    } else {
                        $value = $value / 8;
                    }

                    return $value;
                },
            )),
        );

    }

}
