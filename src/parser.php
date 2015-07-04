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
class Parser
{
    /**
     * @var string
     */
    protected $default = '';

    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @param string $default
     * @param array  $ruleList
     */
    public function __construct($default = '', array $ruleList = array())
    {
        $this->rules   = $ruleList;
        $this->default = $default;
    }

    /**
     * @param mixed  $data
     * @param string $forceRule
     * @return array
     * @throws Exception
     */
    public function parse($data = null, $forceRule = null)
    {
        $value = null;
        $rule  = null;

        if (is_array($data)) {
            $value = isset($data[0]) ? $data[0] : null;
            $rule  = isset($data[1]) ? $data[1] : null;
            return $this->parse($value, $rule);

        } else {
            $data = trim($data);
            $reg  = '#(.*)(' . implode('|', $this->getCodeList()) . ')(.*)#iu';

            if (preg_match($reg, $data, $matches)) {
                $rule  = $matches[2];
                $value = 0;
                if (!empty($matches[1])) {
                    $value = $matches[1];
                } elseif (!empty($matches[3])) {
                    $value = $matches[3];
                }
            }
        }

        if (is_null($value)) {
            $value = $data;
        }

        $value = $this->cleanValue($value);
        $rule  = $this->checkRule($rule);

        if (!empty($forceRule)) {
            $rule = $forceRule;
        }

        return array($value, $rule);
    }

    /**
     * @return array
     */
    public function getCodeList()
    {
        return array_keys($this->rules);
    }

    /**
     * @param string $value
     * @return float
     */
    public function cleanValue($value)
    {
        $value = trim($value);

        $value = preg_replace("#[^0-9-+eE,.]#", '', $value);

        if (!preg_match('#\d[eE][-+]\d#', $value)) { // remove exponential format
            $value = str_replace(array('e', 'E'), '', $value);
        }

        $value = str_replace(',', '.', $value);
        $value = (float)$value;
        $value = round($value, Formatter::ROUND_DEFAULT);

        return $value;
    }

    /**
     * @param string $rule
     * @return string
     */
    public function cleanRule($rule)
    {
        $rule = strtolower($rule);
        $rule = trim($rule);

        return $rule;
    }

    /**
     * @param string $rule
     * @return string
     * @throws Exception
     */
    public function checkRule($rule)
    {
        $rule = $this->cleanRule($rule);

        if (empty($rule)) {
            return $this->default;
        }

        if (isset($this->rules[$rule])) {
            return $rule;
        }

        throw new Exception('Undefined rule: ' . $rule);
    }

    /**
     * @param string $newRule
     */
    public function addRule($newRule)
    {
        $this->rules[$newRule] = $newRule;
    }

    /**
     * @param string $rule
     * @return bool
     */
    public function removeRule($rule)
    {
        if (isset($this->rules[$rule])) {
            unset($this->rules[$rule]);
        }
    }
}
