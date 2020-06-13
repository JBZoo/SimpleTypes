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

namespace JBZoo\SimpleTypes\Config;

/**
 * class Temp
 * @package JBZoo\SimpleTypes
 */
class Temp extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'k';
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        $this->defaultParams['format_positive'] = '%v%s';
        $this->defaultParams['format_negative'] = '-%v%s';

        return [
            // Celsius
            'C' => [
                'symbol' => '°C',
                'rate'   => function (float $value, string $ruleTo): float {
                    if ($ruleTo === 'k') {
                        $value += 273.15;
                    } else {
                        $value -= 273.15;
                    }

                    return $value;
                },
            ],

            // Fahrenheit
            'F' => [
                'symbol' => '°F',
                'rate'   => function (float $value, string $ruleTo): float {
                    if ($ruleTo === 'k') {
                        $value = ($value + 459.67) * (5 / 9);
                    } else {
                        $value = $value * (9 / 5) - 459.67;
                    }

                    return $value;
                },
            ],

            // Rankine
            'R' => [
                'symbol' => '°R',
                'rate'   => function (float $value, string $ruleTo): float {
                    if ($ruleTo === 'k') {
                        $value = $value * 5 / 9;
                    } else {
                        $value = $value * 9 / 5;
                    }

                    return $value;
                },
            ],

            // Kelvin
            'K' => [
                'symbol' => 'K',
                'rate'   => function (float $value): float {
                    return $value;
                },
            ],
        ];
    }
}
