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
 * Class Volume
 * @package JBZoo\SimpleTypes\Config
 */
class Volume extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'lit';
    }

    /**
     * @inheritDoc
     * @link https://en.wikipedia.org/wiki/United_States_customary_units
     */
    public function getRules(): array
    {
        return [
            // SI
            'ml'  => [
                'symbol' => 'mL',
                'rate'   => 0.001,
            ],
            'cm3' => [
                'symbol' => 'cm3',
                'rate'   => 0.1,
            ],
            'm3'  => [
                'symbol' => 'm3',
                'rate'   => 1000,
            ],
            'lit' => [
                'symbol' => 'L',
                'rate'   => 1,
            ],
            // other
            'qt'  => [
                'symbol' => 'US quart',
                'rate'   => 0.946352946,
            ],
            'pt'  => [
                'symbol' => 'US pint',
                'rate'   => 0.56826125,
            ],
            'gal' => [
                'symbol' => 'US gallon',
                'rate'   => 3.785411784,
            ],
            'bbl' => [
                'symbol' => 'Barrel',
                'rate'   => 119.240471196,
            ],
        ];
    }
}
