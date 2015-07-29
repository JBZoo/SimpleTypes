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
 * Class TypeTest
 * @package SmetDenis\SimpleTypes
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
