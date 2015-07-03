<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package    SimpleTypes
 * @author     Denis Smetannikov <denis@jbzoo.com>
 * @copyright  2015 Denis Smetannikov <denis@jbzoo.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link       http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;


/**
 * Class infoTypeTest
 * @package SmetDenis\SimpleTypes
 *
 * @codeCoverageIgnore
 */
class typeTest extends PHPUnit
{

    protected $_type = '';

    protected $_ns = '\\SmetDenis\\SimpleTypes\\';

    /**
     * @param null $arg
     * @return Type
     * @throws Exception
     */
    protected function _($arg = null)
    {
        $configName = $this->_ns . 'Config' . ucfirst($this->_type);
        $className  = $this->_ns . $this->_type;
        Config::registerDefault($this->_type, new $configName);

        return new $className($arg);
    }


    function testCreate()
    {
        $config = new ConfigTestEmpty();

        $files = scandir(realpath(__DIR__ . '/../../src/type'));

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