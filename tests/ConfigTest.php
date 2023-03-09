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

use JBZoo\PHPUnit\Fixture\AbstractConfigTestEmpty;
use JBZoo\PHPUnit\Fixture\AbstractConfigTestInfo;
use JBZoo\PHPUnit\Fixture\AbstractConfigTestWeight;
use JBZoo\PHPUnit\Fixture\AbstractConfigTestWrong;
use JBZoo\SimpleTypes\Config\AbstractConfig;
use JBZoo\SimpleTypes\Type\Info;
use JBZoo\SimpleTypes\Type\Money;
use JBZoo\SimpleTypes\Type\Weight;

final class ConfigTest extends PHPUnit
{
    public function testEmptyValid(): void
    {
        $money = new Money('1 i', new AbstractConfigTestEmpty());
        is('1 i', $money->dump(false));
    }

    public function testRegisterDefault(): void
    {
        AbstractConfig::registerDefault('weight', new AbstractConfigTestWeight());
        AbstractConfig::registerDefault('info', new AbstractConfigTestInfo());

        // weight
        $weight1 = new Weight('1gram');
        $weight2 = new Weight('1kg');

        isSame('1 gram', $weight1->dump(false));
        isSame('1000 gram', $weight2->convert('gram')->dump(false));

        // info
        $info1 = new Info(1);
        $info2 = new Info('1024byte');

        isSame('1 byte', $info1->dump(false));
        isSame('1 kb', $info2->convert('kb')->dump(false));
    }

    public function testEmptyDefault(): void
    {
        isSame(null, AbstractConfig::getDefault('undefined'));
    }

    public function testWrongDefault(): void
    {
        $this->expectException(\JBZoo\SimpleTypes\Exception::class);
        new Money(null, new AbstractConfigTestWrong());
    }
}
