<?php

/**
 * JBZoo Toolbox - SimpleTypes
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    SimpleTypes
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/SimpleTypes
 */

declare(strict_types=1);

namespace JBZoo\SimpleTypes\Config;

use JBZoo\SimpleTypes\Formatter;

/**
 * Class Money
 * @package JBZoo\SimpleTypes\Config
 */
class Money extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'eur';
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        $this->defaultParams['num_decimals'] = 2;
        $this->defaultParams['round_type'] = Formatter::ROUND_CLASSIC;
        $this->defaultParams['decimal_sep'] = '.';
        $this->defaultParams['thousands_sep'] = ' ';
        $this->defaultParams['format_positive'] = '%v %s';
        $this->defaultParams['format_negative'] = '-%v %s';

        return [
            'eur' => [
                'symbol' => '€',
                'rate'   => 1,
            ],

            'usd' => [
                'symbol'          => '$',
                'format_positive' => '%s%v',
                'format_negative' => '-%s%v',
                'rate'            => 0.5,
            ],

            'rub' => [
                'symbol'      => 'руб.',
                'decimal_sep' => ',',
                'rate'        => 0.02,
            ],

            'uah' => [
                'symbol'      => 'грн.',
                'decimal_sep' => ',',
                'rate'        => 0.04,
            ],

            'byr' => [
                'symbol'       => 'Br',
                'round_type'   => Formatter::ROUND_CEIL,
                'round_value'  => '-2',
                'num_decimals' => '0',
                'rate'         => 0.00005,
            ],

            '%' => [
                'symbol'          => '%',
                'format_positive' => '%v%s',
                'format_negative' => '-%v%s',
            ],
        ];
    }
}
