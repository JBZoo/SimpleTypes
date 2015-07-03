<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <smet.denis@gmail.com>.
 *
 * @package    SimpleTypes
 * @author     Denis Smetannikov <smet.denis@gmail.com>
 * @copyright  2015 Denis Smetannikov <smet.denis@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link       http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\SimpleTypes;


/**
 * Class Type
 * @package SmetDenis\SimpleTypes
 */
abstract class Type
{
    /**
     * @var int
     */
    protected $_id = 0;

    /**
     * @var string
     */
    protected $_type = '';

    /**
     * @var float|int
     */
    protected $_value = 0;

    /**
     * @var string
     */
    protected $_rule = '';

    /**
     * @var string
     */
    protected $_default = '';

    /**
     * @var Parser
     */
    protected $_parser = null;

    /**
     * @var Formatter
     */
    protected $_formatter = null;

    /**
     * @var array
     */
    protected $_logs = null;

    /**
     * @var bool
     */
    protected $_isDebug = true;

    /**
     * @type int
     */
    static protected $_counter = 0;

    /**
     * @var array
     */
    static public $_globalConfig = array();

    /**
     * @param mixed  $value
     * @param Config $config
     */
    public function __construct($value = null, Config $config = null)
    {
        $this->_type = strtolower(str_replace(__NAMESPACE__ . '\\', '', get_class($this)));

        // get custom or global config
        $config = $this->_getConfig($config);

        // debug flag (for logging)
        $this->_isDebug = (bool)$config->isDebug;

        // set default rule
        $this->_default = trim(strtolower($config->default));
        !$this->_default && $this->_error('Default rule cannot be empty!');

        // create formatter helper
        $this->_formatter = new Formatter($config->getRules(), $this->_type);

        // check that default rule
        $rules = $this->_formatter->getList(true);
        if (!isset($rules[$this->_default])) {
            $this->_error('Default rule not found!');
        }

        // create parser helper
        $this->_parser = new Parser($this->_default, $rules);

        // parse data
        list($this->_value, $this->_rule) = $this->_parser->parse($value);

        // count unique id
        self::$_counter++;
        $this->_id = self::$_counter;

        // success log
        $this->_log('Id=' . $this->_id . ' has just created; dump="' . $this->dump(false) . '"');
    }

    /**
     * @param Config $config
     * @return Config
     * @throws Exception
     */
    protected function _getConfig(Config $config = null)
    {
        $config = $config ? $config : Config::getDefault($this->_type);
        if (!$config) {
            $this->_error('Undefined config for "' . $this->_type . '"');
        }

        return $config;
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->_id;
    }

    /**
     * @param null $rule
     * @return float
     */
    public function val($rule = null)
    {
        if ($rule && $rule != $this->_rule) {
            return $this->_convert($rule);
        }

        return $this->_value;
    }

    /**
     * @param $rule
     * @return string
     */
    public function text($rule = null)
    {
        $rule = $rule ? $this->_parser->checkRule($rule) : $this->_rule;
        $this->_log('Formatted output in "' . $rule . '" as "text"');
        return $this->_formatter->text($this->val($rule), $rule);
    }

    /**
     * @param $rule
     * @return string
     */
    public function noStyle($rule = null)
    {
        $rule = $rule ? $this->_parser->checkRule($rule) : $this->_rule;
        $this->_log('Formatted output in "' . $rule . '" as "noStyle"');
        return $this->_formatter->noStyle($this->val($rule), $rule);
    }

    /**
     * @param $rule
     * @return string
     */
    public function html($rule = null)
    {
        $rule = $rule ? $this->_parser->checkRule($rule) : $this->_rule;
        $this->_log('Formatted output in "' . $rule . '" as "html"');
        return $this->_formatter->html($this->val($rule), $rule, $this->_id, $this->_value, $this->_rule);
    }

    /**
     * @param null $rule
     * @param null $name
     * @param bool $formatted
     * @return string
     * @throws Exception
     */
    public function htmlInput($rule = null, $name = null, $formatted = false)
    {
        $rule = $rule ? $this->_parser->checkRule($rule) : $this->_rule;
        $this->_log('Formatted output in "' . $rule . '" as "input"');
        return $this->_formatter->htmlInput($this->val($rule), $rule, $this->_id, $this->_value, $this->_rule, $name, $formatted);
    }

    /**
     * @param string $rule
     * @return bool
     */
    public function isRule($rule)
    {
        $rule = $this->_parser->checkRule($rule);
        return $rule == $this->_rule;
    }

    /**
     * @return string
     */
    public function rule()
    {
        return $this->_rule;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return (float)$this->_value === 0.0;
    }

    /**
     * @return bool
     */
    public function isPositive()
    {
        return $this->_value > 0;
    }

    /**
     * @return bool
     */
    public function isNegative()
    {
        return $this->_value < 0;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return $this->_formatter->getList();
    }

    /**
     * @param bool $toString
     * @return array|string
     */
    public function data($toString = false)
    {
        $data = array($this->val(), $this->rule());
        return $toString ? implode(' ', $data) : $data;
    }

    /**
     * @return Type
     */
    public function getClone()
    {
        return clone($this);
    }

    /**
     * @param string $rule
     * @param bool   $addToLog
     * @return float
     */
    protected function _convert($rule, $addToLog = false)
    {
        $from = $this->_parser->checkRule($this->_rule);
        $to   = $this->_parser->checkRule($rule);

        $ruleTo   = $this->_formatter->get($to);
        $ruleFrom = $this->_formatter->get($from);
        $ruleDef  = $this->_formatter->get($this->_default);

        $log = '"' . $from . '"=>"' . $to . '"';

        if (empty($to) || !$ruleTo) {
            $this->_error('Converter - undefined target rule: ' . $log);
        }

        if (!$ruleFrom) {
            $this->_error('Converter - undefined source rule: ' . $log);
        }

        $result = $this->_value;
        if ($from != $to) {

            if (is_callable($ruleTo['rate']) || is_callable($ruleFrom['rate'])) {

                if (is_callable($ruleFrom['rate'])) {
                    $defNorm = $ruleFrom['rate']($this->_value, $this->_default, $from);
                } else {
                    $defNorm = $this->_value * $ruleFrom['rate'] * $ruleDef['rate'];
                }

                if (is_callable($ruleTo['rate'])) {
                    $result = $ruleTo['rate']($defNorm, $to, $this->_default);
                } else {
                    $result = $defNorm / $ruleTo['rate'];
                }

            } else {
                $defNorm = $this->_value * $ruleFrom['rate'] * $ruleDef['rate'];
                $result  = $defNorm / $ruleTo['rate'];
            }

            if ($this->_isDebug && $addToLog) {

                $message = array(
                    'Converted ' . $log . ';',
                    'New value = "' . $result . ' ' . $to . '";',
                    is_callable($ruleTo['rate']) ? 'func(' . $from . ')' : $ruleTo['rate'] . ' ' . $from,
                    '=',
                    is_callable($ruleFrom['rate']) ? 'func(' . $to . ')' : $ruleFrom['rate'] . ' ' . $to,
                );

                $this->_log(implode(' ', $message));
            }
        }

        return $result;
    }

    /**
     * @param Type|string $value
     * @param string      $mode
     * @param integer     $round
     * @return bool
     */
    public function compare($value, $mode = '==', $round = Formatter::ROUND_DEFAULT)
    {
        // prepare value
        $value = $this->_getValidValue($value);

        $mode  = in_array($mode, array('=', '==', '===')) ? '==' : $mode;
        $round = (is_null($round)) ? Formatter::ROUND_DEFAULT : ((int)$round);
        $val1  = round((float)$this->val($this->_rule), $round);
        $val2  = round((float)$value->val($this->_rule), $round);

        $this->_log("Compared \"{$this->dump(false)}\" {$mode} \"{$value->dump(false)}\" // $val1$mode$val2, r=$round, ");

        if ($mode == '==') {
            return $val1 === $val2;

        } else if ($mode == '!=' || $mode == '!==') {
            return $val1 !== $val2;

        } else if ($mode == '<') {
            return $val1 < $val2;

        } else if ($mode == '>') {
            return $val1 > $val2;

        } else if ($mode == '<=') {
            return $val1 <= $val2;

        } else if ($mode == '>=') {
            return $val1 >= $val2;
        }

        $this->_error('Undefined compare mode: ' . $mode);

        return false;
    }

    /**
     * @param bool $getClone
     * @return $this
     */
    public function setEmpty($getClone = false)
    {
        return $this->_modifer(0.0, 'Set empty', $getClone);
    }

    /**
     * @param      $value
     * @param bool $getClone
     * @return $this
     */
    public function add($value, $getClone = false)
    {
        return $this->_add($value, $getClone);
    }

    /**
     * @param      $value
     * @param bool $getClone
     * @return Type
     */
    public function subtract($value, $getClone = false)
    {
        return $this->_add($value, $getClone, true);
    }

    /**
     * @param string $newRule
     * @param bool   $getClone
     * @return $this
     */
    public function convert($newRule, $getClone = false)
    {
        if (empty($newRule)) {
            return $this;
        }

        $newRule = $this->_parser->checkRule($newRule);

        $obj = $getClone ? clone($this) : $this;

        if ($newRule !== $obj->_rule) {
            $obj->_value = $obj->_convert($newRule, true);
            $obj->_rule  = $newRule;
        }

        return $obj;
    }

    /**
     * @param bool $getClone
     * @return Type
     */
    public function invert($getClone = false)
    {
        $logMess = 'Invert sign';
        if ($this->_value > 0) {
            $newValue = -1 * $this->_value;
        } else if ($this->_value < 0) {
            $newValue = abs($this->_value);
        } else {
            $newValue = $this->_value;
        }

        return $this->_modifer($newValue, $logMess, $getClone);
    }

    /**
     * @param bool $getClone
     * @return Type
     */
    public function positive($getClone = false)
    {
        return $this->_modifer(abs($this->_value), 'Set positive/abs', $getClone);
    }

    /**
     * @param bool $getClone
     * @return Type
     */
    public function negative($getClone = false)
    {
        return $this->_modifer(-1 * abs($this->_value), 'Set negative', $getClone);
    }

    /**
     * @param bool $getClone
     * @return Type
     */
    public function abs($getClone = false)
    {
        return $this->positive($getClone);
    }

    /**
     * @param float $number
     * @param bool  $getClone
     * @return Type
     */
    public function multiply($number, $getClone = false)
    {
        $multiplier = $this->_parser->clean($number);
        $newValue   = $this->_value * $multiplier;

        return $this->_modifer($newValue, 'Multiply with "' . $multiplier . '"', $getClone);
    }

    /**
     * @param float $number
     * @param bool  $getClone
     * @return Type
     */
    public function division($number, $getClone = false)
    {
        $divider  = $this->_parser->clean($number);
        $newValue = $this->_value / $divider;

        return $this->_modifer($newValue, 'Division with "' . $divider . '"', $getClone);
    }

    /**
     * @param  $value
     * @return Type
     */
    public function percent($value)
    {
        $value = $this->_getValidValue($value);

        $percent = 0.0;
        if (!$this->isEmpty() && !$value->isEmpty()) {
            $percent = ($this->_value / $value->val($this->_rule)) * 100;
        }

        $result = $this->_getValidValue($percent . '%');
        $this->_log('Calculate percent; ' . $this->dump(false) . ' / ' . $value->dump(false) . ' = ' . $result->dump(false));

        return $result;
    }

    /**
     * @param \Closure $function
     * @param bool     $getClone
     * @return Type
     * @throws Exception
     */
    public function customFunc(\Closure $function, $getClone = false)
    {
        if (is_callable($function)) {
            $this->_log('--> Function start');
            $function($this);
        } else {
            $this->_error('Function is not callable!');
        }

        return $this->_modifer($this->_value, '<-- Function finished', $getClone);
    }

    /**
     * @param mixed $value
     * @param bool  $getClone
     * @return Type
     */
    public function set($value, $getClone = false)
    {
        $value = $this->_getValidValue($value);

        $this->_value = $value->val();
        $this->_rule  = $value->rule();

        return $this->_modifer($this->_value, 'Set new value = "' . $this->dump(false) . '"', $getClone);
    }

    /**
     * @param mixed $value
     * @param bool  $getClone
     * @param bool  $isSubtract
     * @return $this
     * @throws Exception
     */
    protected function _add($value, $getClone = false, $isSubtract = false)
    {
        $value = $this->_getValidValue($value);

        $addValue = 0;

        if ($this->_rule == '%') {

            if ($value->rule() == '%') {
                $addValue = $value->val();
            } else {
                $this->_error('Impossible add "' . $value->dump(false) . '" to "' . $this->dump(false) . '"');
            }

        } else {

            if ($value->rule() !== '%') {
                $addValue = $value->val($this->_rule);
            } else {
                $addValue = $this->_value * $value->val() / 100;
            }
        }

        if ($isSubtract) {
            $addValue *= -1;
        }

        $newValue = $this->_value + $addValue;
        $logMess  = ($isSubtract ? 'Subtract' : 'Add') . ' "' . $value->dump(false) . '"';

        return $this->_modifer($newValue, $logMess, $getClone);
    }

    /**
     * @param mixed $newValue
     * @param null  $logMessage
     * @param bool  $getClone
     * @return Type
     */
    protected function _modifer($newValue, $logMessage = null, $getClone = false)
    {
        $logMessage = $logMessage ? $logMessage . '; ' : '';

        // create new object
        if ($getClone) {
            $clone         = $this->getClone();
            $clone->_value = $newValue;
            $clone->_log($logMessage . 'New value = "' . $clone->dump(false) . '"');
            return $clone;
        }

        $this->_value = $newValue;
        $this->_log($logMessage . 'New value = "' . $this->dump(false) . '"');

        return $this;
    }

    /**
     * @param int    $roundValue
     * @param string $mode
     * @return $this
     */
    public function round($roundValue = null, $mode = Formatter::ROUND_CLASSIC)
    {
        $oldValue = $this->_value;
        $newValue = $this->_formatter->round($this->_value, $this->_rule, $roundValue, $mode);

        $this->_log('Rounded (size=' . (int)$roundValue . '; type=' . $mode . ') "' . $oldValue . '" => "' . $newValue . '"');

        $this->_value = $newValue;

        return $this;
    }

    /**
     * @param Type|string $value
     * @return Type
     * @throws Exception
     */
    protected function _getValidValue($value)
    {
        if (is_object($value)) {
            $thisClass = strtolower(get_class($this));
            $valClass  = strtolower(get_class($value));
            if ($thisClass !== $valClass) {
                $this->_error('No valid object type given: ' . $valClass);
            }

        } else {
            $className = get_class($this);
            $value     = new $className($value, $this->_getConfig());
        }

        return $value;
    }

    /**
     * @param string $message
     * @throws Exception
     */
    protected function _error($message)
    {
        $this->_log($message);
        throw new Exception(get_class($this) . ': ' . $message);
    }

    /**
     * @param bool $showId
     * @return string
     */
    public function dump($showId = true)
    {
        $id = $showId ? '; id=' . $this->_id : '';
        return $this->_value . ' ' . $this->_rule . $id;
    }

    /**
     * @param $message
     */
    protected function _log($message)
    {
        if ($this->_isDebug) {
            $this->_logs[] = (string)$message;
        }
    }

    /**
     * @return mixed
     */
    public function logs()
    {
        return $this->_logs;
    }

    /**
     * @return null|string
     */
    public function __toString()
    {
        $this->_log('__toString() called');

        return $this->text();
    }

    /**
     * Serialize
     * @return array
     */
    public function __sleep()
    {
        $result   = array();
        $reflect  = new \ReflectionClass($this);
        $propList = $reflect->getProperties();

        foreach ($propList as $prop) {
            if ($prop->isStatic() == true) {
                continue;
            }
            $result[] = $prop->name;
        }

        $this->_log('Serialized');

        return $result;
    }

    /**
     * Wake up after serialize
     */
    public function __wakeup()
    {
        $this->_log('--> wakeup start');
        $this->__construct($this->dump(false));
        $this->_log('<-- Wakeup finish');
    }

    /**
     * Clone object
     */
    public function __clone()
    {
        self::$_counter++;

        $oldId     = $this->_id;
        $this->_id = self::$_counter;

        $this->_log('Has cloned from id=' . $oldId . ' and created new with id=' . $this->_id . '; dump=' . $this->dump(false));
    }

    /**
     * @param $name
     * @return float|null|string
     */
    function __get($name)
    {
        //$this->_log('__get() called: "' . $name . '"');
        $name = strtolower($name);

        if ($name == 'value') {
            return $this->val();

        } else if ($name == 'rule') {
            return $this->rule();
        }

        return null;
    }

    /**
     * @param $name
     * @param $value
     */
    function __set($name, $value)
    {
        //$this->_log('__set() called: "' . $name . '" = "' . $value . '"');
        $name = strtolower($name);
        if ($name == 'value') {
            $this->set(array($value));

        } else if ($name == 'rule') {
            $this->convert($value);
        }
    }

    /**
     * Experimental! Methods aliases
     * @param string $name
     * @param array  $arguments
     * @return Type|mixed
     */
    function __call($name, $arguments)
    {
        $name = strtolower($name);
        if ($name == 'value') {
            return call_user_func_array(array($this, 'val'), $arguments);

        } else if ($name == 'plus') {
            return call_user_func_array(array($this, 'add'), $arguments);

        } else if ($name == 'minus') {
            return call_user_func_array(array($this, 'subtract'), $arguments);
        }

        $this->_error('Called undefined method: "' . $name . '"');
        return null;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function __invoke()
    {
        $args      = func_get_args();
        $argsCount = count($args);

        if ($argsCount == 0) {
            $this->_error('Undefined arguments');

        } else if ($argsCount == 1) {
            $rules = $this->_formatter->getList();

            if (isset($rules[$args[0]])) {
                return $this->convert($args[0]);
            } else {
                return $this->set($args[0]);
            }

        } else if ($argsCount == 2) {
            return $this->set(array($args[0], $args[1]));
        }

        $this->_error('Too many arguments');
        return $this;
    }

    /**
     * @param array  $newFormat
     * @param string $rule
     * @return $this
     */
    public function changeRule($rule, array $newFormat)
    {
        $rule = trim(strtolower($rule));
        $this->_formatter->changeRule($rule, $newFormat);
        $this->_log('Rule "' . $rule . '" changed');

        return $this;
    }

    /**
     * @param array  $newFormat
     * @param string $rule
     * @return $this
     */
    public function addRule($rule, array $newFormat = array())
    {
        $rule = trim(strtolower($rule));
        $this->_formatter->addRule($rule, $newFormat);
        $this->_parser->addRule($rule);
        $this->_log('New rule "' . $rule . '" added');

        return $this;
    }

    /**
     * @param string $rule
     * @return $this
     */
    public function removeRule($rule)
    {
        $rule = trim(strtolower($rule));
        $this->_formatter->removeRule($rule);
        $this->_parser->removeRule($rule);
        $this->_log('Rule "' . $rule . '" removed');

        return $this;
    }

}
