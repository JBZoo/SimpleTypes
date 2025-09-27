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
use JBZoo\SimpleTypes\Config\AbstractConfig;
use JBZoo\SimpleTypes\Type\AbstractType;

final class InstancesTest extends PHPUnit
{
    public function testCreateTypes(): int
    {
        $config = new AbstractConfigTestEmpty();
        $files  = \scandir(\realpath(__DIR__ . '/../src/Type'));

        $count = 0;

        foreach ($files as $file) {
            if ($file === '.'
                || $file === '..'
                || $file === 'AbstractType.php'
                || !\str_contains($file, '.php')
            ) {
                continue;
            }

            $className = '\JBZoo\SimpleTypes\Type\\' . \str_replace('.php', '', $file);

            $obj = new $className('', $config);

            isClass(AbstractType::class, $obj);

            $count++;
        }

        return $count;
    }

    /**
     * @depends testCreateTypes
     */
    public function testCreateConfigs(int $typeCount): void
    {
        $files = \scandir(\realpath(__DIR__ . '/../src/Config'));

        $count = 0;

        foreach ($files as $file) {
            if ($file === '.'
                || $file === '..'
                || $file === 'AbstractConfig.php'
                || !\str_contains($file, '.php')
            ) {
                continue;
            }

            $className = '\JBZoo\SimpleTypes\Config\\' . \str_replace('.php', '', $file);

            $obj = new $className();
            isClass(AbstractConfig::class, $obj);

            $count++;
        }

        is($typeCount, $count, 'Some configs or types are not found');
    }
}
