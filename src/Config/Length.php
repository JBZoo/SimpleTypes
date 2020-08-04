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
 * Class Length
 * @package JBZoo\SimpleTypes\Config
 */
class Length extends Config
{
    /**
     * Set default
     */
    public function __construct()
    {
        $this->default = 'm';
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            // SI
            'mm' => [
                'symbol' => 'mm',
                'rate'   => 0.001,
            ],
            'cm' => [
                'symbol' => 'cm',
                'rate'   => 0.01,
            ],
            'dm' => [
                'symbol' => 'dm',
                'rate'   => 0.1,
            ],
            'm'  => [
                'symbol' => 'm',
                'rate'   => 1,
            ],
            'km' => [
                'symbol' => 'km',
                'rate'   => 1000,
            ],

            // others
            'p'  => [
                'symbol' => 'Point',
                'rate'   => 0.000352777778,
            ],
            'li' => [
                'symbol' => 'Link',
                'rate'   => 0.2012,
            ],
            'in' => [
                'symbol' => 'Inches',
                'rate'   => 0.0254,
            ],
            'ft' => [
                'symbol' => 'Foot',
                'rate'   => 0.3048,
            ],
            'yd' => [
                'symbol' => 'Yard',
                'rate'   => 0.9144,
            ],
            'mi' => [
                'symbol' => 'Mile',
                'rate'   => 1609.344,
            ],
        ];
    }
}
