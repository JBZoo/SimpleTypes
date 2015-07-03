<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <smet.denis@gmail.com>.
 *
 * @package    SimpleTypes
 * @author     Denis Smetannikov <smet.denis@gmail.com>
 * @copyright  2015 Denis Smetannikov <smet.denis@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link       http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class PHPUnit
 * @package SmetDenis\SimpleTypes
 */
class PHPUnit extends \PHPUnit_Framework_TestCase
{
    /**
     * @param mixed $arg
     * @return Money
     */
    protected function _($arg = null)
    {
        Config::registerDefault('money', new ConfigMoney());

        return new Money($arg);
    }

    /**
     * @param $testList
     */
    protected function _batchEqualDumps($testList)
    {
        foreach ($testList as $test) {
            $result = isset($test[0]) ? $test[0] : null;
            $arg    = isset($test[1]) ? $test[1] : null;
            $this->assertEquals($this->_($arg)->dump(false), $result);
        }
    }

    /**
     * @param $testList
     */
    protected function _batchEquals($testList)
    {
        foreach ($testList as $test) {
            $this->assertEquals($test[0], $test[1]);
        }
    }

}