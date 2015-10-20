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

namespace JBZoo\SimpleTypes;

/**
 * Class outputTest
 * @package JBZoo\SimpleTypes
 */
class OutputTest extends PHPUnit
{

    public function testText()
    {
        $this->batchEquals(array(
            // eur
            array('10 000.67 €', $this->val('10000.666 eur')->text()),
            array('-10 000.67 €', $this->val('-10000.666 eur')->text()),

            // usd
            array('$10 000.67', $this->val('10000.666 usd')->text()),
            array('-$10 000.67', $this->val('-10000.666 usd')->text()),

            // rub
            array('10 000,67 руб.', $this->val('10000.666 rub')->text()),
            array('-10 000,67 руб.', $this->val('-10000.666 rub')->text()),

            // uah
            array('10 000,67 грн.', $this->val('10000.666 uah')->text()),
            array('-10 000,67 грн.', $this->val('-10000.666 uah')->text()),

            // byr
            array('10 100 Br', $this->val('10000.666 byr')->text()),
            array('-10 000 Br', $this->val('-10000.666 byr')->text()),

            // %
            array('10.67%', $this->val('10.666 %')->text()),
            array('-10.67%', $this->val('-10.666 %')->text()),

            // with converting
            array('$2.00', $this->val('1 eur')->text('usd')),
            array('0.50 €', $this->val('1 usd')->text('eur')),
        ));
    }

    public function testDump()
    {
        $this->assertRegExp('#10000\.666666\d* uah; id=[0-9]*#i', $this->val('10000.666666666 uah')->dump());
        $this->assertEquals('10000.666 uah', $this->val('10000.666 uah')->dump(false));
    }

    public function testData()
    {
        $this->batchEquals(array(
            array(array('10000.666', 'uah'), $this->val('10000.666 uah')->data()),
            array(array('10000.666', 'uah'), $this->val('10000.666 uah')->data(false)),
            array('10000.666 uah', $this->val('10000.666 uah')->data(true)),
        ));
    }

    public function testNoStyle()
    {
        $this->batchEquals(array(
            array('10 000,67', $this->val('10000.666 uah')->noStyle()),
        ));
    }

    public function testHtmlSpan()
    {
        $html = $this->val('100.50 uah')->html('usd');

        // check tag
        $this->assertRegExp('#<span\s#', $html);

        // check important classes
        $this->assertRegExp('#simpleType#', $html);
        $this->assertRegExp('#simpleType-block#', $html);
        $this->assertRegExp('#simpleType-symbol#', $html);
        $this->assertRegExp('#simpleType-value#', $html);
        $this->assertRegExp('#simpleType-money#', $html);

        // attributes
        $this->assertRegExp('#data-simpleType-id="\d*"#', $html);
        $this->assertRegExp('#data-simpleType-value="8\.04"#', $html);
        $this->assertRegExp('#data-simpleType-rule="usd"#', $html);
        $this->assertRegExp('#data-simpleType-orig-value="100\.5"#', $html);
        $this->assertRegExp('#data-simpleType-orig-rule="uah"#', $html);

        // html
        $this->assertRegExp('#<span class="simpleType-symbol">\$</span>#', $html);
        $this->assertRegExp('#<span class="simpleType-value">8\.04</span>#', $html);
    }

    public function testHtmlInput()
    {
        $html = $this->val('100.50 uah')->htmlInput('usd', 'some-param');

        $this->assertRegExp('#<input\s#', $html);

        // check important classes
        $this->assertRegExp('#simpleType#', $html);
        $this->assertRegExp('#simpleType-input#', $html);
        $this->assertRegExp('#simpleType-value#', $html);
        $this->assertRegExp('#simpleType-money#', $html);

        // attributes
        $this->assertRegExp('#name=\"some-param\"#', $html);
        $this->assertRegExp('#data-simpleType-id="\d*"#', $html);
        $this->assertRegExp('#data-simpleType-value="8\.04"#', $html);
        $this->assertRegExp('#data-simpleType-rule="usd"#', $html);
        $this->assertRegExp('#data-simpleType-orig-value="100\.5"#', $html);
        $this->assertRegExp('#data-simpleType-orig-rule="uah"#', $html);
    }

    public function testGetId()
    {
        $this->assertGreaterThan(0, $this->val()->getId());
    }

    public function testGetLogs()
    {
        $logs = $this->val()->logs();
        $this->assertTrue(true, is_array($logs));
    }
}
