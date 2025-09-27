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
final class Weight extends AbstractConfig
{
    public function __construct()
    {
        $this->default = 'g';
    }

    /**
     * {@inheritDoc}
     */
    public function getRules(): array
    {
        return [
            // SI
            'g'   => ['symbol' => 'g', 'rate' => 1],
            'kg'  => ['symbol' => 'Kg', 'rate' => 1000],
            'ton' => ['symbol' => 'Tons', 'rate' => 1000000],

            // other
            'gr' => ['symbol' => 'Grains', 'rate' => 0.06479891],
            'dr' => ['symbol' => 'Drams', 'rate' => 1.7718451953125],
            'oz' => ['symbol' => 'Ounces', 'rate' => 28.349523125],
            'lb' => ['symbol' => 'Pounds', 'rate' => 453.59237],
        ];
    }
}
