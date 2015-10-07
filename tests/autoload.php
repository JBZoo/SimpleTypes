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

// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart

// main autoload
if ($autoload = realpath('./vendor/autoload.php')) {
    require_once $autoload;
} else {
    echo "Please execute \"composer update\"" . PHP_EOL;
    exit(1);
}

if (!defined('ROOT_PATH')) { // for PHPUnit process isolation
    define('ROOT_PATH', realpath('.'));
}

// test tools
require_once 'phpunit.php';
require_once 'fixtures.php';
require_once 'type/typeTest.php';


// @codeCoverageIgnoreEnd
