<?php

/**
 * JBZoo Toolbox - SimpleTypes.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/SimpleTypes
 */

declare(strict_types=1);

namespace JBZoo\SimpleTypes\Config;

/**
 * @psalm-suppress UnusedClass
 */
final class Temp extends AbstractConfig
{
    public function __construct()
    {
        $this->default = 'k';
    }

    /**
     * {@inheritDoc}
     */
    public function getRules(): array
    {
        $this->defaultParams['format_positive'] = '%v%s';
        $this->defaultParams['format_negative'] = '-%v%s';

        return [
            // Celsius
            'C' => [
                'symbol' => '°C',
                'rate'   => static function (float $value, string $ruleTo): float {
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
                'rate'   => static function (float $value, string $ruleTo): float {
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
                'rate'   => static function (float $value, string $ruleTo): float {
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
                'rate'   => static fn (float $value): float => $value,
            ],
        ];
    }
}
