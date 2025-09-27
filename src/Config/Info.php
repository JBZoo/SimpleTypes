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

use JBZoo\SimpleTypes\Formatter;

/**
 * @psalm-suppress UnusedClass
 */
final class Info extends AbstractConfig
{
    public function __construct()
    {
        $this->default = 'byte';
    }

    /**
     * {@inheritDoc}
     */
    public function getRules(): array
    {
        $base = 1024;

        $this->defaultParams['num_decimals'] = 0;
        $this->defaultParams['round_type']   = Formatter::ROUND_NONE;

        return [
            'byte' => [
                'symbol' => 'B',
                'rate'   => 1,
            ],
            'kb' => [
                'symbol' => 'KB',
                'rate'   => $base ** 1,
            ],
            'mb' => [
                'symbol' => 'MB',
                'rate'   => $base ** 2,
            ],
            'gb' => [
                'symbol' => 'GB',
                'rate'   => $base ** 3,
            ],
            'tb' => [
                'symbol' => 'TB',
                'rate'   => $base ** 4,
            ],
            'pb' => [
                'symbol' => 'PB',
                'rate'   => $base ** 5,
            ],
            'eb' => [
                'symbol' => 'EB',
                'rate'   => $base ** 6,
            ],
            'zb' => [
                'symbol' => 'ZB',
                'rate'   => $base ** 7,
            ],
            'yb' => [
                'symbol' => 'YB',
                'rate'   => $base ** 8,
            ],

            'bit' => [
                'symbol' => 'Bit',
                'rate'   => static function (float $value, string $ruleTo) {
                    if ($ruleTo === 'bit') {
                        return $value * 8;
                    }

                    return $value / 8;
                },
            ],
        ];
    }
}
