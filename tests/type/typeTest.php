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
 * Class infoTypeTest
 * @package SmetDenis\SimpleTypes
 *
 * @codeCoverageIgnore
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
        $configName = $this->ns . 'Config' . ucfirst($this->type);
        $className  = $this->ns . $this->type;
        Config::registerDefault($this->type, new $configName);

        return new $className($arg);
    }

    public function testCreate()
    {
        $config = new ConfigTestEmpty();
        $files  = scandir(realpath(__DIR__ . '/../../src/type'));

        foreach ($files as $file) {
            if ($file == '.' || $file == '..' || strpos($file, '.php') === false) {
                continue;
            }

            $className = '\\SmetDenis\\SimpleTypes\\' . ucfirst(str_replace('.php', '', $file));

            $obj = new $className('', $config);
            $this->assertInstanceOf('\\SmetDenis\\SimpleTypes\\Type', $obj);
        }
    }
}
