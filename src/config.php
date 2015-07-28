<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class Config
 * @package SmetDenis\SimpleTypes
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
