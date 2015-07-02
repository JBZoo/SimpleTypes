<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <smet.denis@gmail.com>.
 *
 * @package    SimpleTypes
 * @author     Denis Smetannikov <smet.denis@gmail.com>
 * @copyright  2015 Denis Smetannikov <smet.denis@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link       http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;


/**
 * Class ConfigExample
 * @package SmetDenis\SimpleTypes
 */
class ConfigExample extends Config
{
    public $default = 'eur';
    public $isDebug = true;

    public function getRules()
    {
        return array(
            'eur' => array(
                'symbol'          => '€',
                'round_type'      => Formatter::ROUND_CLASSIC,
                'round_value'     => '2',
                'num_decimals'    => '2',
                'decimal_sep'     => '.',
                'thousands_sep'   => ' ',
                'format_positive' => '%v %s',
                'format_negative' => '-%v %s',
                'rate'            => 1,
            ),

            'usd' => array(
                'symbol'          => '$',
                'round_type'      => Formatter::ROUND_CLASSIC,
                'round_value'     => '2',
                'num_decimals'    => '2',
                'decimal_sep'     => '.',
                'thousands_sep'   => ' ',
                'format_positive' => '%s%v',
                'format_negative' => '-%s%v',
                'rate'            => 0.5,
            ),

            'rub' => array(
                'symbol'          => 'руб.',
                'round_type'      => Formatter::ROUND_CLASSIC,
                'round_value'     => '2',
                'num_decimals'    => '2',
                'decimal_sep'     => ',',
                'thousands_sep'   => ' ',
                'format_positive' => '%v %s',
                'format_negative' => '-%v %s',
                'rate'            => 0.02,
            ),

            'uah' => array(
                'symbol'          => 'грн.',
                'round_type'      => Formatter::ROUND_CLASSIC,
                'round_value'     => '2',
                'num_decimals'    => '2',
                'decimal_sep'     => ',',
                'thousands_sep'   => ' ',
                'format_positive' => '%v %s',
                'format_negative' => '-%v %s',
                'rate'            => 0.04,
            ),

            'byr' => array(
                'symbol'          => 'Br',
                'round_type'      => Formatter::ROUND_CEIL,
                'round_value'     => '-2',
                'num_decimals'    => '0',
                'decimal_sep'     => '.',
                'thousands_sep'   => ' ',
                'format_positive' => '%v %s',
                'format_negative' => '-%v %s',
                'rate'            => 0.00005,
            ),

            '%'   => array(
                'symbol'          => '%',
                'round_type'      => Formatter::ROUND_CLASSIC,
                'round_value'     => '2',
                'num_decimals'    => '2',
                'decimal_sep'     => '.',
                'thousands_sep'   => ' ',
                'format_positive' => '%v%s',
                'format_negative' => '-%v%s',
            )
        );

    }

}
