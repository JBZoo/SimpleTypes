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

use JBZoo\SimpleTypes\Formatter;

/**
 * Class Config
 * @package JBZoo\SimpleTypes
 */
abstract class Config
{
    /**
     * Base for all converting
     * @var string
     */
    public $default = '';

    /**
     * For logging all events
     * @var bool
     */
    public $isDebug = false;

    /**
     * Popular params for most measures
     * @var array
     */
    public $defaultParams = [
        // main
        'symbol'          => '',
        'rate'            => 1,

        // number format
        'num_decimals'    => '2',
        'decimal_sep'     => '.',
        'thousands_sep'   => ' ',

        // templates
        'format_positive' => '%v %s',
        'format_negative' => '-%v %s',

        // round
        'round_type'      => Formatter::ROUND_CLASSIC,
        'round_value'     => Formatter::ROUND_DEFAULT,
    ];

    /**
     * @var array
     */
    protected static $configs = [];

    /**
     * List of rules
     * @return array
     */
    abstract public function getRules();

    /**
     * @param string $type
     * @param Config $config
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public static function registerDefault($type, Config $config)
    {
        $type = strtolower(trim($type));

        self::$configs[$type] = $config;
    }

    /**
     * @param string $type
     * @return Config
     */
    public static function getDefault($type)
    {
        $type = strtolower(trim($type));
        if (array_key_exists($type, self::$configs)) {
            return self::$configs[$type];
        }

        return null;
    }
}
