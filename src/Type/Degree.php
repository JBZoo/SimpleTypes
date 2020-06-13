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

namespace JBZoo\SimpleTypes\Type;

/**
 * Class Degree
 * @package JBZoo\SimpleTypes
 */
class Degree extends Type
{
    /**
     * @return Degree
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function removeCircles()
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
            if ($this->value <= (-1 * $divider)) {
                $this->value = fmod($this->value, $divider);
            } elseif ($this->value >= $divider) {
                $this->value = fmod($this->value, $divider);
            }

            $this->log('Remove circles : ' . $this->dump(false));
        }

        return $this;
    }
}
