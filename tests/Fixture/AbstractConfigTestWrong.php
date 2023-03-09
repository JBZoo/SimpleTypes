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

class AbstractConfigTestWrong extends AbstractConfig
{
    public $default = 'undefined';
    public $isDebug = true;

    public function getRules(): array
    {
        return [
            'byte' => ['rate' => 1],
            'kb'   => ['rate' => 1024],
        ];
    }
}
