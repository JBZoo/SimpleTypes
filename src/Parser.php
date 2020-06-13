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

namespace JBZoo\SimpleTypes;

/**
 * Class Type
 * @package JBZoo\SimpleTypes
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
    protected $rules = [];

    /**
     * @param string $default
     * @param array  $ruleList
     */
    public function __construct($default = '', array $ruleList = [])
    {
        uksort($ruleList, function ($item1, $item2) {
            return strlen($item2) - strlen($item1);
        });

        $this->rules = $ruleList;
        $this->default = $default;
    }

    /**
     * @param mixed  $data
     * @param string $forceRule
     * @return array
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function parse($data = null, $forceRule = null)
    {
        $rule = null;

        if (is_array($data)) {
            $value = $data[0] ?? null;
            $rule = $data[1] ?? null;
            return $this->parse($value, $rule);
        }

        $value = strtolower(trim($data));
        $aliases = $this->getCodeList();
        foreach ($aliases as $alias) {
            if (strpos($value, $alias) !== false) {
                $rule = $alias;
                $value = str_ireplace($rule, '', $value);
                break;
            }
        }

        $value = $this->cleanValue($value);
        $rule = $this->checkRule($rule);

        if ($forceRule) {
            $rule = $forceRule;
        }

        return [$value, $rule];
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
        $result = trim($value);

        $result = preg_replace('#[^0-9-+eE,.]#', '', $result);

        if (!preg_match('#\d[eE][-+]\d#', $result)) { // remove exponential format
            $result = str_replace(['e', 'E'], '', $result);
        }

        $result = (float)str_replace(',', '.', $result);
        $result = round($result, Formatter::ROUND_DEFAULT);

        return $result;
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
     * @throws \JBZoo\SimpleTypes\Exception
     */
    public function checkRule($rule)
    {
        $rule = $this->cleanRule($rule);

        if (!$rule) {
            return $this->default;
        }

        if (array_key_exists($rule, $this->rules)) {
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
        if (array_key_exists($rule, $this->rules)) {
            unset($this->rules[$rule]);
        }
    }
}
