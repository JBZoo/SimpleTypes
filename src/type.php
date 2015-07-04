<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 * @link      http://github.com/smetdenis/simpletypes
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
    protected $id = 0;

    /**
     * @var string
     */
    protected $type = '';

    /**
     * @var float|int
     */
    protected $value = 0;

    /**
     * @var string
     */
    protected $rule = '';

    /**
     * @var string
     */
    protected $default = '';

    /**
     * @var Parser
     */
    protected $parser = null;

    /**
     * @var Formatter
     */
    protected $formatter = null;

    /**
     * @var array
     */
    protected $logs = null;

    /**
     * @var bool
     */
    protected $isDebug = true;

    /**
     * @type int
     */
    static protected $counter = 0;

    /**
     * @param mixed  $value
     * @param Config $config
     */
    public function __construct($value = null, Config $config = null)
    {
        $this->type = strtolower(str_replace(__NAMESPACE__ . '\\', '', get_class($this)));

        // get custom or global config
        $config = $this->getConfig($config);

        // debug flag (for logging)
        $this->isDebug = (bool)$config->isDebug;

        // set default rule
        $this->default = trim(strtolower($config->default));
        !$this->default && $this->error('Default rule cannot be empty!');

        // create formatter helper
        $this->formatter = new Formatter($config->getRules(), $this->type);

        // check that default rule
        $rules = $this->formatter->getList(true);
        if (!isset($rules[$this->default])) {
            throw new Exception($this->type . ': Default rule not found!');
        }

        // create parser helper
        $this->parser = new Parser($this->default, $rules);

        // parse data
        list($this->value, $this->rule) = $this->parser->parse($value);

        // count unique id
        self::$counter++;
        $this->id = self::$counter;

        // success log
        $this->log('Id=' . $this->id . ' has just created; dump="' . $this->dump(false) . '"');
    }

    /**
     * @param Config $config
     * @return Config
     * @throws Exception
     */
    protected function getConfig(Config $config = null)
    {
        $defaultConfig = Config::getDefault($this->type);
        $config        = $config ? $config : $defaultConfig;

        // Hack for getValidValue method
        if (empty($defaultConfig) && $config) {
            Config::registerDefault($this->type, $config);
        }

        return $config;
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param string $rule
     * @return float
     */
    public function val($rule = null)
    {
        $rule = $this->parser->cleanRule($rule);

        if ($rule && $rule != $this->rule) {
            return $this->customConvert($rule);
        }

        return $this->value;
    }

    /**
     * @param $rule
     * @return string
     */
    public function text($rule = null)
    {
        $rule = $rule ? $this->parser->checkRule($rule) : $this->rule;
        $this->log('Formatted output in "' . $rule . '" as "text"');
        return $this->formatter->text($this->val($rule), $rule);
    }

    /**
     * @param $rule
     * @return string
     */
    public function noStyle($rule = null)
    {
        $rule = $rule ? $this->parser->checkRule($rule) : $this->rule;
        $this->log('Formatted output in "' . $rule . '" as "noStyle"');
        return $this->formatter->text($this->val($rule), $rule, false);
    }

    /**
     * @param $rule
     * @return string
     */
    public function html($rule = null)
    {
        $rule = $rule ? $this->parser->checkRule($rule) : $this->rule;
        $this->log('Formatted output in "' . $rule . '" as "html"');
        return $this->formatter->html($this->val($rule), $rule, $this->id, $this->value, $this->rule);
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
        $rule = $rule ? $this->parser->checkRule($rule) : $this->rule;
        $this->log('Formatted output in "' . $rule . '" as "input"');

        return $this->formatter->htmlInput(
            $this->val($rule),
            $rule,
            $this->id,
            $this->value,
            $this->rule,
            $name,
            $formatted
        );
    }

    /**
     * @param string $rule
     * @return bool
     */
    public function isRule($rule)
    {
        $rule = $this->parser->checkRule($rule);
        return $rule === $this->rule;
    }

    /**
     * @return string
     */
    public function rule()
    {
        return $this->rule;
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
        return $this->formatter->getList();
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
    protected function customConvert($rule, $addToLog = false)
    {
        $from = $this->parser->checkRule($this->rule);
        $to   = $this->parser->checkRule($rule);

        $ruleTo   = $this->formatter->get($to);
        $ruleFrom = $this->formatter->get($from);
        $ruleDef  = $this->formatter->get($this->default);

        $log = '"' . $from . '"=>"' . $to . '"';

        $result = $this->value;
        if ($from != $to) {
            if (is_callable($ruleTo['rate']) || is_callable($ruleFrom['rate'])) {
                if (is_callable($ruleFrom['rate'])) {
                    $defNorm = $ruleFrom['rate']($this->value, $this->default, $from);
                } else {
                    $defNorm = $this->value * $ruleFrom['rate'] * $ruleDef['rate'];
                }

                if (is_callable($ruleTo['rate'])) {
                    $result = $ruleTo['rate']($defNorm, $to, $this->default);
                } else {
                    $result = $defNorm / $ruleTo['rate'];
                }

            } else {
                $defNorm = $this->value * $ruleFrom['rate'] * $ruleDef['rate'];
                $result  = $defNorm / $ruleTo['rate'];
            }

            if ($this->isDebug && $addToLog) {
                $message = array(
                    'Converted ' . $log . ';',
                    'New value = "' . $result . ' ' . $to . '";',
                    is_callable($ruleTo['rate']) ? 'func(' . $from . ')' : $ruleTo['rate'] . ' ' . $from,
                    '=',
                    is_callable($ruleFrom['rate']) ? 'func(' . $to . ')' : $ruleFrom['rate'] . ' ' . $to,
                );

                $this->log(implode(' ', $message));
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
        $value = $this->getValidValue($value);

        $mode = trim($mode);
        $mode = in_array($mode, array('=', '==', '===')) ? '==' : $mode;

        $round = (is_null($round)) ? Formatter::ROUND_DEFAULT : ((int)$round);
        $val1  = round((float)$this->val($this->rule), $round);
        $val2  = round((float)$value->val($this->rule), $round);

        $this->log(
            "Compared \"{$this->dump(false)}\" {$mode} " .
            "\"{$value->dump(false)}\" // $val1$mode$val2, r=$round, "
        );

        if ($mode == '==') {
            return $val1 === $val2;

        } elseif ($mode == '!=' || $mode == '!==') {
            return $val1 !== $val2;

        } elseif ($mode == '<') {
            return $val1 < $val2;

        } elseif ($mode == '>') {
            return $val1 > $val2;

        } elseif ($mode == '<=') {
            return $val1 <= $val2;

        } elseif ($mode == '>=') {
            return $val1 >= $val2;
        }

        throw new Exception($this->type . ': Undefined compare mode: ' . $mode);
    }

    /**
     * @param bool $getClone
     * @return $this
     */
    public function setEmpty($getClone = false)
    {
        return $this->modifer(0.0, 'Set empty', $getClone);
    }

    /**
     * @param mixed $value
     * @param bool  $getClone
     * @return $this
     */
    public function add($value, $getClone = false)
    {
        return $this->customAdd($value, $getClone);
    }

    /**
     * @param mixed $value
     * @param bool  $getClone
     * @return Type
     */
    public function subtract($value, $getClone = false)
    {
        return $this->customAdd($value, $getClone, true);
    }

    /**
     * @param string $newRule
     * @param bool   $getClone
     * @return $this
     */
    public function convert($newRule, $getClone = false)
    {
        if (empty($newRule)) {
            $newRule = $this->rule;
        }

        $newRule = $this->parser->checkRule($newRule);

        $obj = $getClone ? clone($this) : $this;

        if ($newRule !== $obj->rule) {
            $obj->value = $obj->customConvert($newRule, true);
            $obj->rule  = $newRule;
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
        if ($this->value > 0) {
            $newValue = -1 * $this->value;
        } elseif ($this->value < 0) {
            $newValue = abs($this->value);
        } else {
            $newValue = $this->value;
        }

        return $this->modifer($newValue, $logMess, $getClone);
    }

    /**
     * @param bool $getClone
     * @return Type
     */
    public function positive($getClone = false)
    {
        return $this->modifer(abs($this->value), 'Set positive/abs', $getClone);
    }

    /**
     * @param bool $getClone
     * @return Type
     */
    public function negative($getClone = false)
    {
        return $this->modifer(-1 * abs($this->value), 'Set negative', $getClone);
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
        $multiplier = $this->parser->cleanValue($number);
        $newValue   = $multiplier * $this->value;

        return $this->modifer($newValue, 'Multiply with "' . $multiplier . '"', $getClone);
    }

    /**
     * @param float $number
     * @param bool  $getClone
     * @return Type
     */
    public function division($number, $getClone = false)
    {
        $divider = $this->parser->cleanValue($number);

        return $this->modifer($this->value / $divider, 'Division with "' . $divider . '"', $getClone);
    }

    /**
     * @param  $value
     * @return Type
     */
    public function percent($value)
    {
        $value = $this->getValidValue($value);

        $percent = 0.0;
        if (!$this->isEmpty() && !$value->isEmpty()) {
            $percent = ($this->value / $value->val($this->rule)) * 100;
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
     * @return Type
     * @throws Exception
     */
    public function customFunc(\Closure $function, $getClone = false)
    {
        if (is_callable($function)) {
            $this->log('--> Function start');
            $function($this);
        }

        return $this->modifer($this->value, '<-- Function finished', $getClone);
    }

    /**
     * @param mixed $value
     * @param bool  $getClone
     * @return Type
     */
    public function set($value, $getClone = false)
    {
        $value = $this->getValidValue($value);

        $this->value = $value->val();
        $this->rule  = $value->rule();

        return $this->modifer($this->value, 'Set new value = "' . $this->dump(false) . '"', $getClone);
    }

    /**
     * @param mixed $value
     * @param bool  $getClone
     * @param bool  $isSubtract
     * @return $this
     * @throws Exception
     */
    protected function customAdd($value, $getClone = false, $isSubtract = false)
    {
        $value = $this->getValidValue($value);

        $addValue = 0;

        if ($this->rule == '%') {
            if ($value->rule() == '%') {
                $addValue = $value->val();
            } else {
                $this->error('Impossible add "' . $value->dump(false) . '" to "' . $this->dump(false) . '"');
            }

        } else {
            if ($value->rule() !== '%') {
                $addValue = $value->val($this->rule);
            } else {
                $addValue = $this->value * $value->val() / 100;
            }
        }

        if ($isSubtract) {
            $addValue *= -1;
        }

        $newValue = $this->value + $addValue;
        $logMess  = ($isSubtract ? 'Subtract' : 'Add') . ' "' . $value->dump(false) . '"';

        return $this->modifer($newValue, $logMess, $getClone);
    }

    /**
     * @param mixed  $newValue
     * @param string $logMessage
     * @param bool   $getClone
     * @return Type
     */
    protected function modifer($newValue, $logMessage = null, $getClone = false)
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
     */
    public function round($roundValue = null, $mode = Formatter::ROUND_CLASSIC)
    {
        $oldValue = $this->value;
        $newValue = $this->formatter->round($this->value, $this->rule, $roundValue, $mode);

        $this->log(
            'Rounded (size=' . (int)$roundValue . '; type=' . $mode . ') "' .
            $oldValue . '" => "' . $newValue . '"'
        );

        $this->value = $newValue;

        return $this;
    }

    /**
     * @param Type|string $value
     * @return Type
     * @throws Exception
     */
    public function getValidValue($value)
    {
        if (is_object($value)) {
            $thisClass = strtolower(get_class($this));
            $valClass  = strtolower(get_class($value));
            if ($thisClass !== $valClass) {
                throw new Exception($this->type . ': No valid object type given: ' . $valClass);
            }

        } else {
            $className = get_class($this);
            $value     = new $className($value, $this->getConfig());
        }

        return $value;
    }

    /**
     * @param string $message
     * @throws Exception
     */
    public function error($message)
    {
        $this->log($message);
        throw new Exception($this->type . ': ' . $message);
    }

    /**
     * @param bool $showId
     * @return string
     */
    public function dump($showId = true)
    {
        $id = $showId ? '; id=' . $this->id : '';
        return $this->value . ' ' . $this->rule . $id;
    }

    /**
     * @param string $message Som message for debugging
     */
    public function log($message)
    {
        if ($this->isDebug) {
            $this->logs[] = (string)$message;
        }
    }

    /**
     * @return mixed
     */
    public function logs()
    {
        return $this->logs;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->log('__toString() called');

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

        $this->log('Serialized');

        return $result;
    }

    /**
     * Wake up after serialize
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
        self::$counter++;

        $oldId    = $this->id;
        $this->id = self::$counter;

        $this->log(
            'Cloned from id=' . $oldId . ' and created new with ' .
            'id=' . $this->id . '; dump=' . $this->dump(false)
        );
    }

    /**
     * @param string $name
     * @return float|string
     * @throws Exception
     */
    public function __get($name)
    {
        $name = strtolower($name);

        if ($name == 'value') {
            return $this->val();

        } elseif ($name == 'rule') {
            return $this->rule();
        }

        throw new Exception($this->type . ': Undefined __get() called: "' . $name . '"');
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @return Type
     * @throws Exception
     */
    public function __set($name, $value)
    {
        $name = strtolower($name);

        if ($name == 'value') {
            return $this->set(array($value));

        } elseif ($name == 'rule') {
            return $this->convert($value);
        }

        throw new Exception($this->type . ': Undefined __set() called: "' . $name . '" = "' . print_r($value, true) . '"');
    }

    /**
     * Experimental! Methods aliases
     * @param string $name
     * @param array  $arguments
     * @return Type|mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        $name = strtolower($name);
        if ($name == 'value') {
            return call_user_func_array(array($this, 'val'), $arguments);

        } elseif ($name == 'plus') {
            return call_user_func_array(array($this, 'add'), $arguments);

        } elseif ($name == 'minus') {
            return call_user_func_array(array($this, 'subtract'), $arguments);
        }

        throw new Exception($this->type . ': Called undefined method: "' . $name . '"');
    }

    /**
     * @return Type
     * @throws Exception
     */
    public function __invoke()
    {
        $args      = func_get_args();
        $argsCount = count($args);

        if ($argsCount == 0) {
            $this->error('Undefined arguments');

        } elseif ($argsCount == 1) {
            $rules = $this->formatter->getList();

            if (isset($rules[$args[0]])) {
                return $this->convert($args[0]);
            } else {
                return $this->set($args[0]);
            }

        } elseif ($argsCount == 2) {
            return $this->set(array($args[0], $args[1]));
        }

        throw new Exception($this->type . ': Too many arguments');
    }

    /**
     * @param array  $newFormat
     * @param string $rule
     * @return $this
     */
    public function changeRule($rule, array $newFormat)
    {
        $rule = $this->parser->cleanRule($rule);
        $this->formatter->changeRule($rule, $newFormat);
        $this->log('Rule "' . $rule . '" changed');

        return $this;
    }

    /**
     * @param string $rule
     * @param array  $newFormat
     * @return Type
     */
    public function addRule($rule, array $newFormat = array())
    {
        $rule = $this->parser->cleanRule($rule);
        $this->formatter->addRule($rule, $newFormat);
        $this->parser->addRule($rule);
        $this->log('New rule "' . $rule . '" added');

        return $this;
    }

    /**
     * @param string $rule
     * @return Type
     */
    public function removeRule($rule)
    {
        $rule = $this->parser->cleanRule($rule);
        $this->formatter->removeRule($rule);
        $this->parser->removeRule($rule);
        $this->log('Rule "' . $rule . '" removed');

        return $this;
    }

    /**
     * @param string $rule
     * @return array
     */
    public function getRule($rule)
    {
        $rule = $this->parser->cleanRule($rule);
        return $this->formatter->get($rule);
    }
}
