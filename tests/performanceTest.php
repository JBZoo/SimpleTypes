<?php

namespace SmetDenis\SimpleTypes;

require_once realpath(__DIR__ . '/../src/autoload.php');
require_once realpath(__DIR__ . '/_configs.php');


/**
 * Class performanceTest
 * @package SmetDenis\SimpleTypes
 */
class performanceTest extends PHPUnit
{
    protected $_max = 1000;

    /**
     * Only for full bootstrap
     */
    public function test()
    {
        $money = new Money(1, new ConfigMoney());
        $this->assertTrue(!empty($money));

        $number = new Number(1, new ConfigTestEmpty());
        $this->assertTrue(!empty($number));
    }

    function testCreateTime()
    {
        $this->_startProfiler();
        for ($i = 0; $i < $this->_max; $i++) {
            new Money(rand(-100, 100) . ' usd', new ConfigMoney());
        }
        $result = $this->_markProfiler($this->_max);

        //$this->_cliMessage('Create time (on ' . $this->_max . '): ' . $result['timeF'] . ' / ' . $result['timeOneF']);

        $this->assertLessThan(0.0005, $result['timeOne'], 'Constructor is too slow!; ' . $result['timeOneF']);
    }

    function testMemoryLeak()
    {
        $this->_startProfiler();
        for ($i = 0; $i < $this->_max; $i++) {
            new Money(rand(-100, 100) . ' usd', new ConfigMoney());
        }
        $result = $this->_markProfiler($this->_max);

        //$this->_cliMessage('Memory leak (on ' . $this->_max . '): ' . $result['memory'] . ' / ' . $result['memoryOneF']);

        $this->assertLessThan(1, $result['memoryOne'], 'Memory leak gets a lot of memory!; ' . $result['memoryOneF']);
    }


    function testSizeOneObject()
    {
        $this->_startProfiler();
        $val = new Number(0, new ConfigTestEmpty());

        $before = $this->_markProfiler(1, 'memory');
        unset($val);

        $after = $this->_markProfiler(1, 'memory');

        $this->assertLessThan(4096, $before, 'Too big object!');
        $this->assertLessThan(256, $after, 'Too big object!');
    }
}