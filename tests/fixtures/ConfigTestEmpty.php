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
 * Class ConfigTestEmpty
 * @package JBZoo\PHPUnit
 * @codeCoverageIgnore
 */
class ConfigTestEmpty extends Config
{
    public $default = 'i';
    public $isDebug = false;

    public function getRules()
    {
        return ['i' => []];
    }
}
