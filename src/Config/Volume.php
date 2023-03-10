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

final class Volume extends AbstractConfig
{
    public function __construct()
    {
        $this->default = 'lit';
    }

    /**
     * {@inheritDoc}
     * @see https://en.wikipedia.org/wiki/United_States_customary_units
     */
    public function getRules(): array
    {
        return [
            // SI
            'ml' => [
                'symbol' => 'mL',
                'rate'   => 0.001,
            ],
            'cm3' => [
                'symbol' => 'cm3',
                'rate'   => 0.1,
            ],
            'm3' => [
                'symbol' => 'm3',
                'rate'   => 1000,
            ],
            'lit' => [
                'symbol' => 'L',
                'rate'   => 1,
            ],
            // other
            'qt' => [
                'symbol' => 'US quart',
                'rate'   => 0.946352946,
            ],
            'pt' => [
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
