<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class PHPUnit
 * @package SmetDenis\SimpleTypes
 */
class PHPUnit extends \PHPUnit_Framework_TestCase
{
    protected $ns = '\\SmetDenis\\SimpleTypes\\';

    protected static $times    = array();
    protected static $memories = array();

    /**
     * @param mixed $arg
     * @return Money
     */
    public function val($arg = null)
    {
        Config::registerDefault('money', new ConfigMoney());

        return new Money($arg);
    }

    /**
     * @param $testList
     */
    public function batchEqualDumps($testList)
    {
        foreach ($testList as $test) {
            $result = isset($test[0]) ? $test[0] : null;
            $arg    = isset($test[1]) ? $test[1] : null;
            $this->assertEquals($this->val($arg)->dump(false), $result);
        }
    }

    /**
     * @param $testList
     */
    public function batchEquals($testList)
    {
        foreach ($testList as $test) {
            $this->assertEquals($test[0], $test[1]);
        }
    }

    /**
     *
     */
    public function startProfiler()
    {
        array_push(self::$times, microtime(true));
        array_push(self::$memories, memory_get_usage(false));
    }

    /**
     * @param int $count
     * @return array
     */
    public function markProfiler($count = 1, $measure = null)
    {
        $time   = microtime(true);
        $memory = memory_get_usage(false);

        $timeDiff   = $time - end(self::$times);
        $memoryDiff = $memory - end(self::$memories);

        array_push(self::$times, $time);
        array_push(self::$memories, $memory);

        // build report
        $count  = (int)abs($count);
        $result = array(
            'count'      => $count,
            'time'       => $timeDiff,
            'memory'     => $memoryDiff,
            'timeOne'    => $timeDiff / $count,
            'memoryOne'  => $memoryDiff / $count,
            'timeF'      => number_format($timeDiff * 1000, 2, '.', ' ') . ' ms',
            'memoryF'    => number_format($memoryDiff / 1024, 2, '.', ' ') . ' KB',
            'timeOneF'   => number_format($timeDiff * 1000 / $count, 2, '.', ' ') . ' ms',
            'memoryOneF' => number_format($memoryDiff / 1024 / $count, 2, '.', ' ') . ' KB',
        );

        if ($measure && isset($result[$measure])) {
            return $result[$measure];
        }

        return $result;
    }

    /**
     * @param $message
     * @return int
     */
    public function cliMessage($message)
    {
        fwrite(STDERR, $message . "\n");
    }
}
