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

namespace JBZoo\PHPUnit;

use JBZoo\SimpleTypes\Config\Config;

/**
 * Class ConfigTestInfo
 * @package JBZoo\PHPUnit
 * @codeCoverageIgnore
 */
class ConfigTestInfo extends Config
{
    public $default = 'byte';
    public $isDebug = true;

    public function getRules()
    {
        return [
            'byte' => ['rate' => 1],
            'kb'   => ['rate' => 1024],
        ];
    }
}
