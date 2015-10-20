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

namespace JBZoo\SimpleTypes;

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
    public $defaultParams = array(
        'symbol'          => '',
        'round_type'      => Formatter::ROUND_CLASSIC,
        'round_value'     => Formatter::ROUND_DEFAULT,
        'num_decimals'    => '2',
        'decimal_sep'     => '.',
        'thousands_sep'   => ' ',
        'format_positive' => '%v %s',
        'format_negative' => '-%v %s',
        'rate'            => 1,
    );

    /**
     * @var array
     */
    static protected $configs = array();

    /**
     * List of rules
     * @return array
     */
    abstract public function getRules();

    /**
     * @param string $type
     * @param Config $config
     * @throws Exception
     */
    public static function registerDefault($type, Config $config)
    {
        $type = trim(strtolower($type));

        self::$configs[$type] = $config;
    }

    /**
     * @param string $type
     * @return Config
     */
    public static function getDefault($type)
    {
        $type = trim(strtolower($type));
        if (array_key_exists($type, self::$configs)) {
            return self::$configs[$type];
        }

        return null;
    }
}
