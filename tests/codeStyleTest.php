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

/**
 * Class CodeStyleTest
 * @package JBZoo\SimpleTypes
 */
class CodeStyleTest extends Codestyle
{
    protected $_packageName = 'SimpleTypes';
    protected $_packageAuthor = 'Denis Smetannikov <denis@jbzoo.com>';

    public function testCyrillic()
    {
        $GLOBALS['_jbzoo_fileExcludes'][] = 'outputTest.php';
        $GLOBALS['_jbzoo_fileExcludes'][] = 'Money.php';

        parent::testCyrillic();
    }


}