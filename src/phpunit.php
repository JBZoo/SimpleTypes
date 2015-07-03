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
    public static $_times = array();
    public static $_memories = array();

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

    /**
     *
     */
    protected function _startProfiler()
    {
        array_push(self::$_times, microtime(true));
        array_push(self::$_memories, memory_get_usage(false));
    }

    /**
     * @param int $count
     * @return array
     */
    protected function _markProfiler($count = 1, $measure = null)
    {
        $time   = microtime(true);
        $memory = memory_get_usage(false);

        $timeDiff   = $time - end(self::$_times);
        $memoryDiff = $memory - end(self::$_memories);

        array_push(self::$_times, $time);
        array_push(self::$_memories, $memory);

        // build report
        $count  = (int)abs($count);
        $result = array(
            'count'   => $count,
            'time'    => $timeDiff,
            'memory'  => $memoryDiff,
            'timeF'   => number_format($timeDiff * 1000, 2, '.', ' ') . ' ms',
            'memoryF' => number_format($memoryDiff / 1024, 2, '.', ' ') . ' KB'
        );

        $result['timeOne']    = $timeDiff / $count;
        $result['memoryOne']  = $memoryDiff / $count;
        $result['timeOneF']   = number_format($timeDiff * 1000 / $count, 2, '.', ' ') . ' ms';
        $result['memoryOneF'] = number_format($memoryDiff / 1024 / $count, 2, '.', ' ') . ' KB';

        if ($measure && isset($result[$measure])) {
            return $result[$measure];
        }

        return $result;
    }

    /**
     * @param $message
     * @return int
     */
    protected function _cliMessage($message)
    {
        fwrite(STDERR, $message . "\n");
    }

}

