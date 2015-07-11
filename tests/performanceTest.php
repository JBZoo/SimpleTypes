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
 * Class performanceTest
 * @package SmetDenis\SimpleTypes
 */
class PerformanceTest extends PHPUnit
{
    protected $max = 100;

    protected $minCreateTime = 0.005; // for really(!!!) slow or old hardware. Normal value is 0.0005 !
    protected $minCreateMem = 512;
    protected $maxObjectMem = 8192;
    protected $maxObjectMemUnset = 256;

    /**
     * Only for full bootstrap
     */
    public function test()
    {
        $money = new Money(1, new ConfigMoney());
        $this->assertTrue(!empty($money));

        $number = new Weight(1, new ConfigTestEmpty());
        $this->assertTrue(!empty($number));
    }

    public function testCreateTime()
    {
        $this->startProfiler();
        for ($i = 0; $i < $this->max; $i++) {
            new Money(rand(-100, 100) . ' usd', new ConfigMoney());
        }
        $result = $this->markProfiler($this->max);

        //$this->cliMessage($message = 'Create time (' . $this->max . ', ' . $this->minCreateTime . '): '
        //    . $result['timeF'] . ' / ' . $result['timeOneF']);

        //$message = 'Constructor is too slow!; ' . $result['timeOneF'];
        //$this->assertLessThan($this->minCreateTime, $result['timeOne'], $message);
    }

    public function testMemoryLeak()
    {
        $this->startProfiler();
        for ($i = 0; $i < $this->max; $i++) {
            new Money(rand(-100, 100) . ' usd', new ConfigMoney());
        }
        $result = $this->markProfiler($this->max);

        //$this->cliMessage('Memory leak (on ' . $this->max . '): ' . $result['memory'] . ' / ' . $result['memoryF']);
        $msaage = 'Memory leak gets a lot of memory!; ' . $result['memoryF'];
        $this->assertLessThan($this->minCreateMem, $result['memory'], $msaage);
    }

    public function testSizeOneObject()
    {
        $this->startProfiler();
        $val = new Weight(1, new ConfigTestEmpty());

        $before = $this->markProfiler(1, 'memory');
        unset($val);

        $after = $this->markProfiler(1, 'memory');

        //$this->cliMessage('SizeOneObject = ' . $before . ' / ' . $after);
        $this->assertLessThan($this->maxObjectMem, $before, 'Too big object!');
        $this->assertLessThan($this->maxObjectMemUnset, $after, 'Too big object!');
    }

    /**
     * @param $message
     * @return int
     */
    protected function cliMessage($message)
    {
        fwrite(STDERR, $message . "\n");
    }
}
