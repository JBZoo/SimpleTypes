<?php

/**
 * JBZoo Toolbox - SimpleTypes.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/SimpleTypes
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

final class OutputTest extends PHPUnit
{
    public function testText(): void
    {
        isSame('10 000.67 €', val('10000.666 eur')->text());
        isSame('-10 000.67 €', val('-10000.666 eur')->text());
        isSame('$10 000.67', val('10000.666 usd')->text());
        isSame('-$10 000.67', val('-10000.666 usd')->text());
        isSame('10 000,67 руб.', val('10000.666 rub')->text());
        isSame('-10 000,67 руб.', val('-10000.666 rub')->text());
        isSame('10 000,67 грн.', val('10000.666 uah')->text());
        isSame('-10 000,67 грн.', val('-10000.666 uah')->text());
        isSame('10 100 Br', val('10000.666 byr')->text());
        isSame('-10 000 Br', val('-10000.666 byr')->text());
        isSame('10.67%', val('10.666 %')->text());
        isSame('-10.67%', val('-10.666 %')->text());
        isSame('$2.00', val('1 eur')->text('usd'));
        isSame('0.50 €', val('1 usd')->text('eur'));
    }

    public function testDump(): void
    {
        isLike('#10000\.666666\d* uah; id=[0-9]*#i', val('10000.666666666 uah')->dump());
        is('10000.666 uah', val('10000.666 uah')->dump(false));
    }

    public function testData(): void
    {
        isSame(['10000.666', 'uah'], val('10000.666 uah')->data());
        isSame(['10000.666', 'uah'], val('10000.666 uah')->data(false));
        isSame('10000.666 uah', val('10000.666 uah')->data(true));
    }

    public function testNoStyle(): void
    {
        isSame('10 000,67', val('10000.666 uah')->noStyle());
    }

    public function testHtmlSpan(): void
    {
        $html = val('100.50 uah')->html('usd');

        // check tag
        isLike('#<span\s#', $html);

        // check important classes
        isLike('#simpleType#', $html);
        isLike('#simpleType-block#', $html);
        isLike('#simpleType-symbol#', $html);
        isLike('#simpleType-value#', $html);
        isLike('#simpleType-money#', $html);

        // attributes
        isLike('#data-simpleType-id="\d*"#', $html);
        isLike('#data-simpleType-value="8\.04"#', $html);
        isLike('#data-simpleType-rule="usd"#', $html);
        isLike('#data-simpleType-orig-value="100\.5"#', $html);
        isLike('#data-simpleType-orig-rule="uah"#', $html);

        // html
        isLike('#<span class="simpleType-symbol">\$</span>#', $html);
        isLike('#<span class="simpleType-value">8\.04</span>#', $html);
    }

    public function testHtmlInput(): void
    {
        $html = val('100.50 uah')->htmlInput('usd', 'some-param');

        isLike('#<input\s#', $html);

        // check important classes
        isLike('#simpleType#', $html);
        isLike('#simpleType-input#', $html);
        isLike('#simpleType-value#', $html);
        isLike('#simpleType-money#', $html);

        // attributes
        isLike('#name=\"some-param\"#', $html);
        isLike('#data-simpleType-id="\d*"#', $html);
        isLike('#data-simpleType-value="8\.04"#', $html);
        isLike('#data-simpleType-rule="usd"#', $html);
        isLike('#data-simpleType-orig-value="100\.5"#', $html);
        isLike('#data-simpleType-orig-rule="uah"#', $html);
    }

    public function testGetId(): void
    {
        isTrue(val()->getId() > 0);
    }

    public function testGetLogs(): void
    {
        $logs = val()->logs();
        isTrue(\is_array($logs));
    }
}
