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

// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart

// main autoload
if (file_exists('vendor/autoload.php')) {
    $path = realpath('.');
    require_once 'vendor/autoload.php';

} else if (file_exists('../src/autoload.php')) {
    $path = realpath('../');
    require_once '../vendor/autoload.php';

} else {
    $path = realpath('../../');
    require_once '../../vendor/autoload.php';
}

define('ROOT_PATH', $path);

// test tools
require_once 'configs.php';
require_once 'type/typeTest.php';


// @codeCoverageIgnoreEnd