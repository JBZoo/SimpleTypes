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
if (file_exists('src/autoload.php')) {
    require_once 'src/autoload.php';

} else if (file_exists('../src/autoload.php')) {
    require_once '../src/autoload.php';

} else {
    require_once '../../src/autoload.php';
}

// test tools
require_once 'configs.php';
require_once 'type/typeTest.php';


// @codeCoverageIgnoreEnd