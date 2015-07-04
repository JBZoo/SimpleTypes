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
 * Class Formatter
 * @package SmetDenis\SimpleTypes
 */
class Formatter
{
    const ROUND_DEFAULT = 8;
    const ROUND_NONE    = 'none';
    const ROUND_CEIL    = 'ceil';
    const ROUND_FLOOR   = 'floor';
    const ROUND_CLASSIC = 'classic';

    /**
     * @var array
     */
    protected $default = array(
        'symbol'          => '',
        'round_type'      => self::ROUND_NONE,
        'round_value'     => self::ROUND_DEFAULT,
        'num_decimals'    => '2',
        'decimal_sep'     => '.',
        'thousands_sep'   => ' ',
        'format_positive' => '%v %s',
        'format_negative' => '-%v %s',
        'rate'            => 1,
    );

    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @var string
     */
    protected $type = null;

    /**
     * @param array  $rules
     * @param string $type
     */
    public function __construct(array $rules = array(), $type = null)
    {
        $this->type = $type;

        // prepare rules
        $this->rules = array_change_key_case((array)$rules, CASE_LOWER);
        foreach ($this->rules as $name => $rule) {
            $this->rules[$name] = array_merge($this->default, $rule);
        }
    }

    /**
     * @param string $rule
     * @return array
     * @throws Exception
     */
    public function get($rule)
    {
        if (isset($this->rules[$rule])) {
            return (array)$this->rules[$rule];
        }

        throw new Exception('Undefined rule: "' . $rule . '"');
    }

    /**
     * @param bool $keysOnly
     * @return array
     */
    public function getList($keysOnly = false)
    {
        if ($keysOnly) {
            $keys = array_keys($this->rules);
            return array_combine($keys, $keys);
        }

        return $this->rules;
    }

    /**
     * @param float  $value
     * @param string $rule
     * @return string
     * @throws Exception
     */
    public function text($value, $rule, $showSymbol = true)
    {
        $data  = $this->format($value, $rule);
        $rData = $this->get($rule);
        $symbol = $showSymbol ? $rData['symbol'] : '';

        $result = str_replace(
            array('%v', '%s'),
            array($data['value'], $symbol),
            $data['template']
        );

        $result = trim($result);

        return $result;
    }

    /**
     * @param float  $value
     * @param string $rule
     * @param int    $id
     * @param float  $origValue
     * @param string $origRule
     * @return string
     * @throws Exception
     */
    public function html($value, $rule, $id, $origValue, $origRule)
    {
        $data  = $this->format($value, $rule);
        $rData = $this->get($rule);

        $result = str_replace(
            array('%v', '%s'),
            array(
                '<span class="simpleType-value">' . $data['value'] . "</span>",
                '<span class="simpleType-symbol">' . $rData['symbol'] . "</span>",
            ),
            $data['template']
        );

        return '<span ' . $this->htmlAttributes(
            array(
                'class'                      => array(
                    'simpleType',
                    'simpleType-block',
                    'simpleType-' . $this->type,
                ),
                'data-simpleType-id'         => $id,
                'data-simpleType-value'      => $value,
                'data-simpleType-rule'       => $rule,
                'data-simpleType-orig-value' => $origValue,
                'data-simpleType-orig-rule'  => $origRule,
            )
        ) . '>' . $result . '</span>';
    }

    /**
     * @param float  $value
     * @param string $rule
     * @param int    $id
     * @param float  $origValue
     * @param string $origRule
     * @param string $inputName
     * @param bool   $formatted
     * @return string
     */
    public function htmlInput($value, $rule, $id, $origValue, $origRule, $inputName, $formatted)
    {
        $inputValue = $formatted ? $this->text($value, $rule) : $this->text($value, $rule, false);

        return '<input ' . $this->htmlAttributes(
            array(
                'value'                      => $inputValue,
                'name'                       => $inputName,
                'type'                       => 'text',
                'class'                      => array(
                    'simpleType',
                    'simpleType-' . $this->type,
                    'simpleType-input'
                ),
                'data-simpleType-id'         => $id,
                'data-simpleType-value'      => $value,
                'data-simpleType-rule'       => $rule,
                'data-simpleType-orig-value' => $origValue,
                'data-simpleType-orig-rule'  => $origRule,
            )
        ) . ' />';
    }

    /**
     * @param array $attributes
     * @return string
     */
    public function htmlAttributes($attributes)
    {
        $result = '';

        if (!empty($attributes)) {
            foreach ($attributes as $key => $param) {
                $value = implode(' ', (array)$param);
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                $value = trim($value);
                $result .= ' ' . $key . '="' . $value . '"';
            }
        }

        return trim($result);
    }

    /**
     * @param float    $value
     * @param string   $rule
     * @param null|int $roundValue
     * @param string   $roundType
     * @return float
     * @throws Exception
     */
    public function round($value, $rule, $roundValue = null, $roundType = null)
    {
        $format = $this->get($rule);

        if (empty($roundType)) {
            $roundType = isset($format['round_type']) ? $format['round_type'] : self::ROUND_NONE;
        }

        if (is_null($roundValue)) {
            $roundValue = isset($format['round_value']) ? $format['round_value'] : self::ROUND_DEFAULT;
        }

        $roundValue = (int)$roundValue;

        if (self::ROUND_CEIL == $roundType) {
            $base  = pow(10, $roundValue);
            $value = ceil($value * $base) / $base;

        } elseif (self::ROUND_CLASSIC == $roundType) {
            $value = round($value, $roundValue);

        } elseif (self::ROUND_FLOOR == $roundType) {
            $base  = pow(10, $roundValue);
            $value = floor($value * $base) / $base;

        } elseif (self::ROUND_NONE == $roundType) {
            $value = round($value, Formatter::ROUND_DEFAULT); // hack, because 123.400000001 !== 123.4

        } else {
            throw new Exception('Undefined round mode: "' . $roundType . '"');
        }

        return $value;
    }

    /**
     * Convert value to money format from config
     * @param float  $value
     * @param string $rule
     * @return array
     */
    protected function format($value, $rule)
    {
        $format = (array)$this->get($rule);
        $value  = !empty($value) ? $value : 0.0;

        $roundedValue = $this->round($value, $rule);
        $isPositive   = ($value >= 0);
        $valueStr     = number_format(
            abs($roundedValue),
            $format['num_decimals'],
            $format['decimal_sep'],
            $format['thousands_sep']
        );

        $template = ($isPositive) ? $format['format_positive'] : $format['format_negative'];

        return array(
            'value'      => $valueStr,
            'template'   => $template,
            'isPositive' => $isPositive,
        );
    }

    /**
     * @param string $rule
     * @param array  $newFormat
     */
    public function changeRule($rule, array $newFormat)
    {
        $oldFormat = $this->get($rule);

        $this->rules[$rule] = array_merge($oldFormat, (array)$newFormat);
    }

    /**
     * @param string $rule
     * @param array  $newFormat
     * @throws Exception
     */
    public function addRule($rule, array $newFormat = array())
    {
        if (!$rule) {
            throw new Exception('Empty rule name');
        }

        if (isset($this->rules[$rule])) {
            throw new Exception('Format "' . $rule . '" already exists');
        }

        $this->rules[$rule] = array_merge($this->default, (array)$newFormat);
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
