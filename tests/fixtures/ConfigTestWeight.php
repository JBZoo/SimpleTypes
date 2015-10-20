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

use JBZoo\SimpleTypes\Config\Config;

/**
 * Class ConfigTestWeight
 * @package JBZoo\PHPUnit
 * @codeCoverageIgnore
 */
class ConfigTestWeight extends Config
{
    public $default = 'gram';
    public $isDebug = true;

    public function getRules()
    {
        return array(
            'kg'   => array('rate' => function ($value, $to) {

                if ($to == 'gram') {
                    return $value * 1000;
                }

                return $value / 1000;
            }),

            'gram' => array('rate' => function ($value) {
                return $value;
            }),
        );
    }
}
