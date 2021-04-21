<?php

/**
 * JBZoo Toolbox - SimpleTypes
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    SimpleTypes
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/SimpleTypes
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

/**
 * Class SimpleTypesReadmeTest
 *
 * @package JBZoo\PHPUnit
 */
class SimpleTypesReadmeTest extends AbstractReadmeTest
{
    protected $packageName = 'SimpleTypes';

    protected function setUp(): void
    {
        parent::setUp();
        $this->params['strict_types'] = true;
    }
}
