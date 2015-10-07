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
 * Class performanceTest
 * @package JBZoo\SimpleTypes
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
        $this->markTestSkipped('Memory test skipped');

        $this->startProfiler();
        for ($i = 0; $i < $this->max; $i++) {
            new Money(rand(-100, 100) . ' usd', new ConfigMoney());
        }
        $result = $this->markProfiler($this->max);

        //$this->cliMessage('Memory leak (on ' . $this->max . '): ' . $result['memory'] . ' / ' . $result['memoryF']);
        $message = 'Memory leak gets a lot of memory!; ' . $result['memoryF'];
        $this->assertLessThan($this->minCreateMem, $result['memory'], $message);
    }

    public function testSizeOneObject()
    {
        $this->markTestSkipped('Size of  One Object test skipped');

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
