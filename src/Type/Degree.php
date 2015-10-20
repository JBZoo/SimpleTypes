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
        $devider = 0;
        if ($this->isRule('d')) {
            $devider = 360;
        } elseif ($this->isRule('r')) {
            $devider = 2;
        } elseif ($this->isRule('g')) {
            $devider = 400;
        } elseif ($this->isRule('t')) {
            $devider = 1;
        }

        if ($devider > 0) {
            if ($this->_value <= (-1 * $devider)) {
                $this->_value = fmod($this->_value, $devider);
            } elseif ($this->_value >= $devider) {
                $this->_value = fmod($this->_value, $devider);
            }

            $this->log('Remove circles : ' . $this->dump(false));
        }

        return $this;
    }
}
