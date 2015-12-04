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

use JBZoo\SimpleTypes\Config\Config;

/**
 * Class TypeTest
 * @package JBZoo\SimpleTypes
 */
class TypeTest extends PHPUnit
{
    protected $_type = null;

    /**
     * @param null $arg
     * @return \JBZoo\SimpleTypes\Type\Type
     */
    public function val($arg = null)
    {
        $configName = '\\JBZoo\\SimpleTypes\\Config\\' . ucfirst($this->_type);
        $className  = '\\JBZoo\\SimpleTypes\\Type\\' . $this->_type;

        Config::registerDefault($this->_type, new $configName);

        return new $className($arg);
    }
}
