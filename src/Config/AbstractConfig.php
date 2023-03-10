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

namespace JBZoo\SimpleTypes\Config;

use JBZoo\SimpleTypes\Formatter;

abstract class AbstractConfig
{
    /** @var array */
    protected static $configs = [];

    /**
     * Base for all converting.
     * @var string
     */
    public $default = '';

    /**
     * For logging all events.
     * @var bool
     */
    public $isDebug = false;

    /**
     * Popular params for most measures.
     * @var array
     */
    public $defaultParams = [
        // main
        'symbol' => '',
        'rate'   => 1,

        // number format
        'num_decimals'  => '2',
        'decimal_sep'   => '.',
        'thousands_sep' => ' ',

        // templates
        'format_positive' => '%v %s',
        'format_negative' => '-%v %s',

        // round
        'round_type'  => Formatter::ROUND_CLASSIC,
        'round_value' => Formatter::ROUND_DEFAULT,
    ];

    /**
     * List of rules.
     * @return array
     */
    abstract public function getRules();

    public static function registerDefault(string $type, self $config): void
    {
        $type                 = \strtolower(\trim($type));
        self::$configs[$type] = $config;
    }

    public static function getDefault(string $type): ?self
    {
        $type = \strtolower(\trim($type));

        return self::$configs[$type] ?? null;
    }
}
