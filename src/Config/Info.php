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

use JBZoo\SimpleTypes\Formatter;

/**
 * class Money
 * @package JBZoo\SimpleTypes
 */
class Info extends Config
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
        $this->defaultParams['round_type'] = Formatter::ROUND_NONE;

        return [
            'byte' => [
                'symbol' => 'B',
                'rate'   => 1,
            ],
            'kb'   => [
                'symbol' => 'KB',
                'rate'   => pow($base, 1),
            ],
            'mb'   => [
                'symbol' => 'MB',
                'rate'   => pow($base, 2),
            ],
            'gb'   => [
                'symbol' => 'GB',
                'rate'   => pow($base, 3),
            ],
            'tb'   => [
                'symbol' => 'TB',
                'rate'   => pow($base, 4),
            ],
            'pb'   => [
                'symbol' => 'PB',
                'rate'   => pow($base, 5),
            ],
            'eb'   => [
                'symbol' => 'EB',
                'rate'   => pow($base, 6),
            ],
            'zb'   => [
                'symbol' => 'ZB',
                'rate'   => pow($base, 7),
            ],
            'yb'   => [
                'symbol' => 'YB',
                'rate'   => pow($base, 8),
            ],

            'bit' => [
                'symbol' => 'Bit',
                'rate'   => function ($value, $ruleTo) {

                    if ($ruleTo === 'bit') {
                        return $value * 8;
                    }

                    return $value / 8;
                },
            ],
        ];
    }
}
