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

namespace JBZoo\SimpleTypes\Type;

final class Degree extends AbstractType
{
    public function removeCircles(): self
    {
        $divider = 0;
        if ($this->isRule('d')) {
            $divider = 360;
        } elseif ($this->isRule('r')) {
            $divider = 2;
        } elseif ($this->isRule('g')) {
            $divider = 400;
        } elseif ($this->isRule('t')) {
            $divider = 1;
        }

        if ($divider > 0) {
            if ($this->internalValue <= (-1 * $divider)) {
                $this->internalValue = \fmod($this->internalValue, $divider);
            } elseif ($this->internalValue >= $divider) {
                $this->internalValue = \fmod($this->internalValue, $divider);
            }

            $this->log("Remove circles: {$this->dump(false)}");
        }

        return $this;
    }
}
