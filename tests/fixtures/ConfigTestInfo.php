<?php
/**
 * JBZoo SimpleTypes
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   SimpleTypes
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

use JBZoo\SimpleTypes\Config;

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
        return array(
            'byte' => array('rate' => 1),
            'kb'   => array('rate' => 1024),
        );
    }
}
