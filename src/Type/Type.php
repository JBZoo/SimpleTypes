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

use JBZoo\SimpleTypes\Config\Config;
use JBZoo\SimpleTypes\Exception;
use JBZoo\SimpleTypes\Formatter;
use JBZoo\SimpleTypes\Parser;

/**
 * Class Type
 * @package JBZoo\SimpleTypes
 */
abstract class Type
{
    /**
     * @var int
     */
    protected $_uniqueId = 0;

    /**
     * @var string
     */
    protected $_type = '';

    /**
     * @var float|int
     */
    protected $value = 0;

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
    protected $_parser;

    /**
     * @var Formatter
     */
    protected $_formatter;

    /**
     * @var array
     */
    protected $_logs = array();

    /**
     * @var bool
     */
    protected $_isDebug = false;

    /**
     * @type int
     */
    static protected $_counter = 0;

    /**
     * @param string $value
     * @param Config $config
     * @throws \JBZoo\SimpleTypes\Exception
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
        !$this->_default && $this->error('Default rule cannot be empty!');

        // create formatter helper
        $this->_formatter = new Formatter($config->getRules(), $config->defaultParams, $this->_type);
        // check that default rule
        $rules = $this->_formatter->getList(true);
        if (!array_key_exists($this->_default, $rules)) {
            throw new Exception($this->_type . ': Default rule not found!');
        }

        // create parser helper
        $this->_parser = new Parser($this->_default, $rules);

        // parse data
        list($this->value, $this->rule) = $this->_parser->parse($value);

        // count unique id
        self::$_counter++;
        $this->_uniqueId = self::$_counter;

        // success log
        $this->log('Id=' . $this->_uniqueId . ' has just created; dump="' . $this->dump(false) . '"');
    }

    /**
     * @param Config $config
     * @return Config
     * @throws \JBZoo\SimpleTypes\Exception
     */
    protected function _getConfig(Config $config = null)
    {
        $defaultConfig = Config::getDefault($this->_type);
        $config        = $config ? $config : $defaultConfig;

        // Hack for getValidValue method
        if (!$defaultConfig && $config) {
            Config::registerDefault($this->_type, $config);
        }

        return $config;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_uniqueId;
    }

    /**
     * @param string $rule
     * @return float
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function val($rule = null)
    {
        $rule = $this->_parser->cleanRule($rule);

        if ($rule && $rule !== $this->_rule) {
            return $this->_customConvert($rule);
        }

        return $this->value;
    }

    /**
     * @param $rule
     * @return string
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function text($rule = null)
    {
        $rule = $rule ? $this->_parser->checkRule($rule) : $this->_rule;
        $this->log('Formatted output in "' . $rule . '" as "text"');
        return $this->_formatter->text($this->val($rule), $rule);
    }

    /**
     * @param $rule
     * @return string
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function noStyle($rule = null)
    {
        $rule = $rule ? $this->_parser->checkRule($rule) : $this->_rule;
        $this->log('Formatted output in "' . $rule . '" as "noStyle"');
        return $this->_formatter->text($this->val($rule), $rule, false);
    }

    /**
     * @param $rule
     * @return string
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function html($rule = null)
    {
        $rule = $rule ? $this->_parser->checkRule($rule) : $this->_rule;
        $this->log('Formatted output in "' . $rule . '" as "html"');
        return $this->_formatter->html(
            array('value' => $this->val($rule), 'rule' => $rule),
            array('value' => $this->value, 'rule' => $this->_rule),
            array('id' => $this->_uniqueId)
        );
    }

    /**
     * @param null $rule
     * @param null $name
     * @param bool $formatted
     * @return string
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function htmlInput($rule = null, $name = null, $formatted = false)
    {
        $rule = $rule ? $this->_parser->checkRule($rule) : $this->_rule;
        $this->log('Formatted output in "' . $rule . '" as "input"');

        return $this->_formatter->htmlInput(
            array('value' => $this->val($rule), 'rule' => $rule),
            array('value' => $this->value, 'rule' => $this->_rule),
            array('id' => $this->_uniqueId, 'name' => $name, 'formatted' => $formatted)
        );
    }

    /**
     * @param string $rule
     * @return bool
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function isRule($rule)
    {
        $rule = $this->_parser->checkRule($rule);
        return $rule === $this->_rule;
    }

    /**
     * @return string
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function getRule()
    {
        return $this->_rule;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return (float)$this->value === 0.0;
    }

    /**
     * @return bool
     */
    public function isPositive()
    {
        return $this->value > 0;
    }

    /**
     * @return bool
     */
    public function isNegative()
    {
        return $this->value < 0;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->_formatter->getList();
    }

    /**
     * @param bool $toString
     * @return array|
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function data($toString = false)
    {
        $data = array($this->val(), $this->getRule());
        return $toString ? implode(' ', $data) : $data;
    }

    /**
     * @return $this
     */
    public function getClone()
    {
        return clone($this);
    }

    /**
     * @param string $rule
     * @param bool   $addToLog
     * @return float
     * @throws \JBZoo\SimpleTypes\Exception
     */
    protected function _customConvert($rule, $addToLog = false)
    {
        $from   = $this->_parser->checkRule($this->_rule);
        $target = $this->_parser->checkRule($rule);

        $ruleTo   = $this->_formatter->get($target);
        $ruleFrom = $this->_formatter->get($from);
        $ruleDef  = $this->_formatter->get($this->_default);

        $log = '"' . $from . '"=>"' . $target . '"';

        $result = $this->value;
        if ($from !== $target) {
            if (is_callable($ruleTo['rate']) || is_callable($ruleFrom['rate'])) {
                if (is_callable($ruleFrom['rate'])) {
                    $defNorm = $ruleFrom['rate']($this->value, $this->_default, $from);
                } else {
                    $defNorm = $this->value * $ruleFrom['rate'] * $ruleDef['rate'];
                }

                if (is_callable($ruleTo['rate'])) {
                    $result = $ruleTo['rate']($defNorm, $target, $this->_default);
                } else {
                    $result = $defNorm / $ruleTo['rate'];
                }

            } else {
                $defNorm = $this->value * $ruleFrom['rate'] * $ruleDef['rate'];
                $result  = $defNorm / $ruleTo['rate'];
            }

            if ($this->_isDebug && $addToLog) {
                $message = array(
                    'Converted ' . $log . ';',
                    'New value = "' . $result . ' ' . $target . '";',
                    is_callable($ruleTo['rate']) ? 'func(' . $from . ')' : $ruleTo['rate'] . ' ' . $from,
                    '=',
                    is_callable($ruleFrom['rate']) ? 'func(' . $target . ')' : $ruleFrom['rate'] . ' ' . $target,
                );

                $this->log(implode(' ', $message));
            }
        }

        return $result;
    }

    /**
     * @param mixed   $value
     * @param string  $mode
     * @param integer $round
     * @return bool
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function compare($value, $mode = '==', $round = Formatter::ROUND_DEFAULT)
    {
        // prepare value
        $value = $this->getValidValue($value);

        $mode = trim($mode);
        $mode = in_array($mode, array('=', '==', '==='), true) ? '==' : $mode;

        $round = (null === $round) ? Formatter::ROUND_DEFAULT : ((int)$round);
        $val1  = round((float)$this->val($this->_rule), $round);
        $val2  = round((float)$value->val($this->_rule), $round);

        $this->log(
            "Compared \"{$this->dump(false)}\" {$mode} " .
            "\"{$value->dump(false)}\" // $val1 $mode $val2, r=$round, "
        );

        if ($mode === '==') {
            return $val1 === $val2;

        } elseif ($mode === '!=' || $mode === '!==') {
            return $val1 !== $val2;

        } elseif ($mode === '<') {
            return $val1 < $val2;

        } elseif ($mode === '>') {
            return $val1 > $val2;

        } elseif ($mode === '<=') {
            return $val1 <= $val2;

        } elseif ($mode === '>=') {
            return $val1 >= $val2;
        }

        throw new Exception($this->_type . ': Undefined compare mode: ' . $mode);
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
     * @param mixed $value
     * @param bool  $getClone
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function add($value, $getClone = false)
    {
        return $this->_customAdd($value, $getClone);
    }

    /**
     * @param mixed $value
     * @param bool  $getClone
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function subtract($value, $getClone = false)
    {
        return $this->_customAdd($value, $getClone, true);
    }

    /**
     * @param string $newRule
     * @param bool   $getClone
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function convert($newRule, $getClone = false)
    {
        if (!$newRule) {
            $newRule = $this->_rule;
        }

        $newRule = $this->_parser->checkRule($newRule);

        $obj = $getClone ? clone($this) : $this;

        if ($newRule !== $obj->_rule) {
            $obj->value = $obj->_customConvert($newRule, true);
            $obj->_rule  = $newRule;
        }

        return $obj;
    }

    /**
     * @param bool $getClone
     * @return $this
     */
    public function invert($getClone = false)
    {
        $logMess = 'Invert sign';
        if ($this->value > 0) {
            $newValue = -1 * $this->value;
        } elseif ($this->value < 0) {
            $newValue = abs($this->value);
        } else {
            $newValue = $this->value;
        }

        return $this->_modifer($newValue, $logMess, $getClone);
    }

    /**
     * @param bool $getClone
     * @return $this
     */
    public function positive($getClone = false)
    {
        return $this->_modifer(abs($this->value), 'Set positive/abs', $getClone);
    }

    /**
     * @param bool $getClone
     * @return $this
     */
    public function negative($getClone = false)
    {
        return $this->_modifer(-1 * abs($this->value), 'Set negative', $getClone);
    }

    /**
     * @param bool $getClone
     * @return $this
     */
    public function abs($getClone = false)
    {
        return $this->positive($getClone);
    }

    /**
     * @param float $number
     * @param bool  $getClone
     * @return $this
     */
    public function multiply($number, $getClone = false)
    {
        $multiplier = $this->_parser->cleanValue($number);
        $newValue   = $multiplier * $this->value;

        return $this->_modifer($newValue, 'Multiply with "' . $multiplier . '"', $getClone);
    }

    /**
     * @param float $number
     * @param bool  $getClone
     * @return $this
     */
    public function division($number, $getClone = false)
    {
        $divider = $this->_parser->cleanValue($number);

        return $this->_modifer($this->value / $divider, 'Division with "' . $divider . '"', $getClone);
    }

    /**
     * @param  $value
     * @param  $revert
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function percent($value, $revert = false)
    {
        $value = $this->getValidValue($value);

        $percent = 0.0;
        if (!$this->isEmpty() && !$value->isEmpty()) {
            $percent = ($this->value / $value->val($this->_rule)) * 100;
        }

        if ($revert) {
            $percent = 100 - $percent;
        }

        $result = $this->getValidValue($percent . '%');
        $this->log(
            'Calculate percent; ' . $this->dump(false) . ' / ' .
            $value->dump(false) . ' = ' . $result->dump(false)
        );

        return $result;
    }

    /**
     * @param \Closure $function
     * @param bool     $getClone
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function customFunc(\Closure $function, $getClone = false)
    {
        if (is_callable($function)) {
            $this->log('--> Function start');
            $function($this);
        }

        return $this->_modifer($this->value, '<-- Function finished', $getClone);
    }

    /**
     * @param mixed $value
     * @param bool  $getClone
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function set($value, $getClone = false)
    {
        $value = $this->getValidValue($value);

        $this->value = $value->val();
        $this->_rule  = $value->getRule();

        return $this->_modifer($this->value, 'Set new value = "' . $this->dump(false) . '"', $getClone);
    }

    /**
     * @param mixed $value
     * @param bool  $getClone
     * @param bool  $isSubtract
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    protected function _customAdd($value, $getClone = false, $isSubtract = false)
    {
        $value = $this->getValidValue($value);

        $addValue = 0;

        if ($this->_rule === '%') {
            if ($value->getRule() === '%') {
                $addValue = $value->val();
            } else {
                $this->error('Impossible add "' . $value->dump(false) . '" to "' . $this->dump(false) . '"');
            }

        } else {
            if ($value->getRule() !== '%') {
                $addValue = $value->val($this->_rule);
            } else {
                $addValue = $this->value * $value->val() / 100;
            }
        }

        if ($isSubtract) {
            $addValue *= -1;
        }

        $newValue = $this->value + $addValue;
        $logMess  = ($isSubtract ? 'Subtract' : 'Add') . ' "' . $value->dump(false) . '"';

        return $this->_modifer($newValue, $logMess, $getClone);
    }

    /**
     * @param mixed  $newValue
     * @param string $logMessage
     * @param bool   $getClone
     * @return $this
     */
    protected function _modifer($newValue, $logMessage = null, $getClone = false)
    {
        $logMessage = $logMessage ? $logMessage . '; ' : '';

        // create new object
        if ($getClone) {
            $clone        = $this->getClone();
            $clone->value = $newValue;
            $clone->log($logMessage . 'New value = "' . $clone->dump(false) . '"');
            return $clone;
        }

        $this->value = $newValue;
        $this->log($logMessage . 'New value = "' . $this->dump(false) . '"');

        return $this;
    }

    /**
     * @param int    $roundValue
     * @param string $mode
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function round($roundValue = null, $mode = Formatter::ROUND_CLASSIC)
    {
        $oldValue = $this->value;
        $newValue = $this->_formatter->round($this->value, $this->_rule, array(
            'roundValue' => $roundValue,
            'roundType'  => $mode,
        ));

        $this->log(
            'Rounded (size=' . (int)$roundValue . '; type=' . $mode . ') "' .
            $oldValue . '" => "' . $newValue . '"'
        );

        $this->value = $newValue;

        return $this;
    }

    /**
     * @param Type|string $value
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function getValidValue($value)
    {
        if (is_object($value)) {
            $thisClass = strtolower(get_class($this));
            $valClass  = strtolower(get_class($value));
            if ($thisClass !== $valClass) {
                throw new Exception($this->_type . ': No valid object type given: ' . $valClass);
            }

        } else {
            $className = get_class($this);
            $value     = new $className($value, $this->_getConfig());
        }

        return $value;
    }

    /**
     * @param string $message
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function error($message)
    {
        $this->log($message);
        throw new Exception($this->_type . ': ' . $message);
    }

    /**
     * @param bool $showId
     * @return string
     */
    public function dump($showId = true)
    {
        $uniqueId = $showId ? '; id=' . $this->_uniqueId : '';
        return $this->value . ' ' . $this->_rule . $uniqueId;
    }

    /**
     * @param string $message Som message for debugging
     */
    public function log($message)
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
     * @return string
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function __toString()
    {
        $this->log('__toString() called');

        return (string)$this->text();
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
            if ($prop->isStatic() === true) {
                continue;
            }
            $result[] = $prop->name;
        }

        $this->log('Serialized');

        return $result;
    }

    /**
     * Wake up after serialize
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function __wakeup()
    {
        $this->log('--> wakeup start');
        $this->__construct($this->dump(false));
        $this->log('<-- Wakeup finish');
    }

    /**
     * Clone object
     */
    public function __clone()
    {
        self::$_counter++;

        $oldId          = $this->_uniqueId;
        $this->_uniqueId = self::$_counter;

        $this->log(
            'Cloned from id=' . $oldId . ' and created new with ' .
            'id=' . $this->_uniqueId . '; dump=' . $this->dump(false)
        );
    }

    /**
     * @param string $name
     * @return float|string
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function __get($name)
    {
        $name = strtolower($name);

        if ($name === 'value') {
            return $this->val();

        } elseif ($name === 'rule') {
            return $this->getRule();
        }

        throw new Exception($this->_type . ': Undefined __get() called: "' . $name . '"');
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function __set($name, $value)
    {
        $name = strtolower($name);

        if ($name === 'value') {
            return $this->set(array($value));

        } elseif ($name === 'rule') {
            return $this->convert($value);
        }

        throw new Exception($this->_type . ': Undefined __set() called: "' . $name . '" = "' . $value . '"');
    }

    /**
     * Experimental! Methods aliases
     * @param string $name
     * @param array  $arguments
     * @return $this|mixed
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function __call($name, $arguments)
    {
        $name = strtolower($name);
        if ($name === 'value') {
            return call_user_func_array(array($this, 'val'), $arguments);

        } elseif ($name === 'plus') {
            return call_user_func_array(array($this, 'add'), $arguments);

        } elseif ($name === 'minus') {
            return call_user_func_array(array($this, 'subtract'), $arguments);
        }

        throw new Exception($this->_type . ': Called undefined method: "' . $name . '"');
    }

    /**
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function __invoke()
    {
        $args      = func_get_args();
        $argsCount = count($args);

        if ($argsCount === 0) {
            $this->error('Undefined arguments');

        } elseif ($argsCount === 1) {
            $rules = $this->_formatter->getList();

            if (array_key_exists($args[0], $rules)) {
                return $this->convert($args[0]);
            } else {
                return $this->set($args[0]);
            }

        } elseif ($argsCount === 2) {
            return $this->set(array($args[0], $args[1]));
        }

        throw new Exception($this->_type . ': Too many arguments');
    }

    /**
     * @param array  $newFormat
     * @param string $rule
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function changeRule($rule, array $newFormat)
    {
        $rule = $this->_parser->cleanRule($rule);
        $this->_formatter->changeRule($rule, $newFormat);
        $this->log('Rule "' . $rule . '" changed');

        return $this;
    }

    /**
     * @param string $rule
     * @param array  $newFormat
     * @return $this
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function addRule($rule, array $newFormat = array())
    {
        $form = $this->_formatter;
        $rule = $this->_parser->cleanRule($rule);
        $form->addRule($rule, $newFormat);
        $this->_parser->addRule($rule);
        $this->log('New rule "' . $rule . '" added');

        return $this;
    }

    /**
     * @param string $rule
     * @return $this
     */
    public function removeRule($rule)
    {
        $rule = $this->_parser->cleanRule($rule);
        $this->_formatter->removeRule($rule);
        $this->_parser->removeRule($rule);
        $this->log('Rule "' . $rule . '" removed');

        return $this;
    }

    /**
     * @param string $rule
     * @return array
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function getRuleData($rule)
    {
        $rule = $this->_parser->cleanRule($rule);
        return $this->_formatter->get($rule);
    }
}
