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

namespace JBZoo\PHPUnit\Fixture;

use JBZoo\SimpleTypes\Config\AbstractConfig;

class AbstractConfigTestWeight extends AbstractConfig
{
    public $default = 'gram';
    public $isDebug = true;

    public function getRules(): array
    {
        return [
            'kg' => [
                'rate' => static function ($value, $to) {
                    if ($to === 'gram') {
                        return $value * 1000;
                    }

                    return $value / 1000;
                },
            ],

            'gram' => [
                'rate' => static fn ($value) => $value,
            ],
        ];
    }
}
