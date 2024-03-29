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

final class Degree extends AbstractConfig
{
    public function __construct()
    {
        $this->default = 'd';
    }

    /**
     * {@inheritDoc}
     */
    public function getRules(): array
    {
        return [
            // degree
            'd' => [
                'format_positive' => '%v%s',
                'format_negative' => '-%v%s',
                'symbol'          => '°',
            ],

            // radian
            'r' => [
                'symbol' => 'pi',
                'rate'   => static function (float $value, string $ruleTo): float {
                    if ($ruleTo === 'd') {
                        return $value * 180;
                    }

                    return $value / 180;
                },
            ],

            // grads
            'g' => [
                'symbol' => 'Grad',
                'rate'   => static function (float $value, string $ruleTo): float {
                    if ($ruleTo === 'd') {
                        return $value * 0.9;
                    }

                    return $value / 0.9;
                },
            ],

            // turn (loop)
            't' => [
                'symbol' => 'Turn',
                'rate'   => 360,
            ],
        ];
    }
}
