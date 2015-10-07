<?php
/**
 * SimpleTypes
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

namespace JBZoo\SimpleTypes;

/**
 * Class ConfigTestEmpty
 * @package JBZoo\SimpleTypes
 * @codeCoverageIgnore
 */
class ConfigTestEmpty extends Config
{
    public $default = 'i';
    public $isDebug = false;

    public function getRules()
    {
        return array('i' => array());
    }
}

/**
 * Class ConfigTestWeight
 * @package JBZoo\SimpleTypes
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
            })
        );
    }
}

/**
 * Class ConfigTestInfo
 * @package JBZoo\SimpleTypes
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
            'kb'   => array('rate' => 1024)
        );
    }
}

/**
 * Class ConfigTestWrong
 * @package JBZoo\SimpleTypes
 * @codeCoverageIgnore
 */
class ConfigTestWrong extends Config
{
    public $default = 'undefined';
    public $isDebug = true;

    public function getRules()
    {
        return array(
            'byte' => array('rate' => 1),
            'kb'   => array('rate' => 1024)
        );
    }
}
