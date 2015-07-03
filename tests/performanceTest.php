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

    public function testCreateTime()
    {
        $this->startProfiler();
        for ($i = 0; $i < $this->_max; $i++) {
            new Money(rand(-100, 100) . ' usd', new ConfigMoney());
        }
        $result = $this->markProfiler($this->_max);

        $this->markTestSkipped('Create time (on ' . $this->_max . '): ' . $result['timeF'] . ' / ' . $result['timeOneF']);
        $this->assertLessThan(0.0005, $result['timeOne'], 'Constructor is too slow!; ' . $result['timeOneF']);
    }

    public function testMemoryLeak()
    {
        $this->startProfiler();
        for ($i = 0; $i < $this->_max; $i++) {
            new Money(rand(-100, 100) . ' usd', new ConfigMoney());
        }
        $result = $this->markProfiler($this->_max);

        $this->markTestSkipped('Memory leak (on ' . $this->_max . '): ' . $result['memory'] . ' / ' . $result['memoryOneF']);
        $this->assertLessThan(1, $result['memoryOne'], 'Memory leak gets a lot of memory!; ' . $result['memoryOneF']);
    }

    public function testSizeOneObject()
    {
        $this->startProfiler();
        $val = new Number(0, new ConfigTestEmpty());

        $before = $this->markProfiler(1, 'memory');
        unset($val);

        $after = $this->markProfiler(1, 'memory');

        $this->markTestSkipped('SizeOneObject = ' . $before . ' / ' . $after);
        $this->assertLessThan(4096, $before, 'Too big object!');
        $this->assertLessThan(256, $after, 'Too big object!');
    }
}
