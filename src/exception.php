<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package    SimpleTypes
 * @author     Denis Smetannikov <denis@jbzoo.com>
 * @copyright  2015 Denis Smetannikov <denis@jbzoo.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link       http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;

/**
 * Class Exception
 * @package SmetDenis\SimpleTypes
 */
class Exception extends \Exception
{
    /**
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        $trace = debug_backtrace();
        if (isset($trace[1])) {
            $message = $trace[1]['class'] . ' -> ' . $trace[1]['function'] . '(); ' . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
