<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class Degree
 * @package SmetDenis\SimpleTypes
 */
class Degree extends Type
{
    /**
     * @return Degree
     */
    public function removeCircles()
    {
        $devider = null;
        if ($this->isRule('d')) {
            $devider = 360;
        } elseif ($this->isRule('r')) {
            $devider = 2;
        } elseif ($this->isRule('g')) {
            $devider = 400;
        } elseif ($this->isRule('t')) {
            $devider = 1;
        }

        if ($devider) {
            if ($this->value <= (-1 * $devider)) {
                $this->value = fmod($this->value, $devider);
            } elseif ($this->value >= $devider) {
                $this->value = fmod($this->value, $devider);
            }

            $this->log('Remove circles : ' . $this->dump(false));

        } else {
            $this->log('Remove circles. Undefined rule: ' . $this->rule());
        }

        return $this;
    }
}
