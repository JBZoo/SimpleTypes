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

namespace JBZoo\SimpleTypes;

/**
 * Class TypeTest
 * @package JBZoo\SimpleTypes
 */
class TypeTest extends PHPUnit
{
    protected $type = '';

    /**
     * @param null $arg
     * @return Type
     * @throws Exception
     */
    public function val($arg = null)
    {
        $configName = $this->namespace . 'Config' . ucfirst($this->type);
        $className  = $this->namespace . $this->type;
        Config::registerDefault($this->type, new $configName);

        return new $className($arg);
    }
}
