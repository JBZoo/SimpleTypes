<?php
/**
 * JBZoo SimpleTypes
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   SimpleTypes
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\SimpleTypes;

/**
 * Class ConfigMoney
 * @package JBZoo\SimpleTypes
 */
class ConfigMoney extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'eur';
    }

    /**
     * List of rules
     * @return array
     */
    public function getRules()
    {
        $this->defaultParams['num_decimals']    = 2;
        $this->defaultParams['num_decimals']    = 2;
        $this->defaultParams['round_type']      = Formatter::ROUND_CLASSIC;
        $this->defaultParams['decimal_sep']     = '.';
        $this->defaultParams['thousands_sep']   = ' ';
        $this->defaultParams['format_positive'] = '%v %s';
        $this->defaultParams['format_negative'] = '-%v %s';

        return array(
            'eur' => array(
                'symbol' => '€',
                'rate'   => 1,
            ),

            'usd' => array(
                'symbol'          => '$',
                'format_positive' => '%s%v',
                'format_negative' => '-%s%v',
                'rate'            => 0.5,
            ),

            'rub' => array(
                'symbol'      => 'руб.',
                'decimal_sep' => ',',
                'rate'        => 0.02,
            ),

            'uah' => array(
                'symbol'      => 'грн.',
                'decimal_sep' => ',',
                'rate'        => 0.04,
            ),

            'byr' => array(
                'symbol'       => 'Br',
                'round_type'   => Formatter::ROUND_CEIL,
                'round_value'  => '-2',
                'num_decimals' => '0',
                'rate'         => 0.00005,
            ),

            '%'   => array(
                'symbol'          => '%',
                'format_positive' => '%v%s',
                'format_negative' => '-%v%s',
            )
        );
    }
}
