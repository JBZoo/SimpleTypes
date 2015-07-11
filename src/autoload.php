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

// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart

/**
 * Autoloader for types
 */
spl_autoload_register(

    function ($class) {

        static $classMap = null;

        if (is_null($classMap)) {
            $classMap = array(
                // core
                'config'       => 'config.php',
                'exception'    => 'exception.php',
                'formatter'    => 'formatter.php',
                'parser'       => 'parser.php',
                'phpunit'      => 'phpunit.php',
                'type'         => 'type.php',

                // types
                'area'         => 'type/area.php',
                'degree'       => 'type/degree.php',
                'info'         => 'type/info.php',
                'length'       => 'type/length.php',
                'money'        => 'type/money.php',
                'temp'         => 'type/temp.php',
                'time'         => 'type/time.php',
                'volume'       => 'type/volume.php',
                'weight'       => 'type/weight.php',

                'configarea'   => 'config/area.php',
                'configdegree' => 'config/degree.php',
                'configinfo'   => 'config/info.php',
                'configlength' => 'config/length.php',
                'configmoney'  => 'config/money.php',
                'configtemp'   => 'config/temp.php',
                'configtime'   => 'config/time.php',
                'configvolume' => 'config/volume.php',
                'configweight' => 'config/weight.php',
            );
        }

        $namespace = 'smetdenis\\simpletypes\\';
        $class     = strtolower($class);
        $className = str_replace($namespace, '', $class);

        if (0 === strpos($class, $namespace) && isset($classMap[$className])) {
            require_once realpath(__DIR__ . '/' . $classMap[$className]);
        }
    }

);
// @codeCoverageIgnoreEnd
