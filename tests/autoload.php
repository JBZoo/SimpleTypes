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

// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart

if (!defined('ROOT_PATH')) { // for PHPUnit process isolation
    define('ROOT_PATH', realpath('.'));
}

// main autoload
if ($autoload = realpath(ROOT_PATH . '/vendor/autoload.php')) {
    require_once $autoload;
} else {
    echo 'Please execute "composer update" !' . PHP_EOL;
    exit(1);
}

// test tools
require_once 'phpunit.php';
require_once 'fixtures/ConfigTestEmpty.php';
require_once 'fixtures/ConfigTestInfo.php';
require_once 'fixtures/ConfigTestWeight.php';
require_once 'fixtures/ConfigTestWrong.php';
require_once 'type/typeTest.php';

// @codeCoverageIgnoreEnd
