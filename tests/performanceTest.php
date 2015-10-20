<?php
/**
 * JBZoo SimpleTypes
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

namespace JBZoo\PHPUnit;

use JBZoo\SimpleTypes\Config\Money as ConfigMoney;
use JBZoo\SimpleTypes\Type\Money;
use JBZoo\SimpleTypes\Type\Weight;

/**
 * Class performanceTest
 * @package JBZoo\SimpleTypes
 */
class PerformanceTest extends PHPUnit
{
    protected $max = 1000;

    /**
     * Only for full bootstrap
     */
    public function test()
    {
        $money = new Money(1, new ConfigMoney());
        isTrue(!empty($money));

        $number = new Weight(1, new ConfigTestEmpty());
        isTrue(!empty($number));
    }

    public function testCreateTime()
    {
        if ($this->isXDebug()) {
            return;
        }

        $this->startProfiler();
        for ($i = 0; $i < $this->max; $i++) {
            new Money(rand(-100, 100) . ' usd', new ConfigMoney());
        }
        $result = $this->loopProfiler($this->max);
        alert($result, 'Creation');
    }

    public function testMemoryLeak()
    {
        if ($this->isXDebug()) {
            return;
        }

        $this->startProfiler();
        for ($i = 0; $i < $this->max; $i++) {
            new Money(rand(-100, 100) . ' usd', new ConfigMoney());
        }
        $result = $this->loopProfiler($this->max);
        alert($result, 'Leak');
    }

    public function testSizeOneObject()
    {
        if ($this->isXDebug()) {
            return;
        }

        $this->startProfiler();
        $val = new Weight(1, new ConfigTestEmpty());

        alert($this->loopProfiler(1), 'Size one obj, before');

        unset($val);
        alert($this->loopProfiler(1), 'Size one obj, after');
    }
}
