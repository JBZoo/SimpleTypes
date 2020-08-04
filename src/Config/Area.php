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
 * Class Area
 * @package JBZoo\SimpleTypes\Config
 */
class Area extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'm2';
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            // SI
            'mm2' => [
                'symbol' => 'mm2',
                'rate'   => 0.000001,
            ],
            'cm2' => [
                'symbol' => 'cm2',
                'rate'   => 0.0001,
            ],
            'm2'  => [
                'symbol' => 'm2',
                'rate'   => 1,
            ],
            'km2' => [
                'symbol' => 'km2',
                'rate'   => 1000000,
            ],

            // other
            'ft2' => [
                'symbol' => 'sq ft',
                'rate'   => 0.09290341,
            ],
            'ch2' => [
                'symbol' => 'sq ch',
                'rate'   => 404.6873,
            ],
            'acr' => [
                'symbol' => 'Acre',
                'rate'   => 4046.873,
            ],
            'ar'  => [
                'symbol' => 'Ar',
                'rate'   => 100,
            ],
            'ga'  => [
                'symbol' => 'Ga',
                'rate'   => 10000,
            ],
        ];
    }
}
