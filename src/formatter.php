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
    protected $_default = array(
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
    protected $_rules = array();

    /**
     * @var array
     */
    protected $_type = null;

    /**
     * @param array $rules
     * @param string $type
     */
    public function __construct(array $rules = array(), $type = null)
    {
        // prepare rules
        $this->_rules = array_change_key_case((array)$rules, CASE_LOWER);
        foreach ($this->_rules as $name => $rule) {
            $this->_rules[$name] = array_merge($this->_default, $rule);
        }

        // set type
        $this->_type = $type;
    }

    /**
     * @param $rule
     * @return array|null
     * @throws Exception
     */
    public function get($rule)
    {
        if (isset($this->_rules[$rule])) {
            return (array)$this->_rules[$rule];
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
            $keys = array_keys($this->_rules);
            return array_combine($keys, $keys);
        }

        return $this->_rules;
    }

    /**
     * @param float $value
     * @param string $rule
     * @return string
     * @throws Exception
     */
    public function text($value, $rule)
    {
        $data  = $this->_format($value, $rule);
        $rData = $this->get($rule);

        $result = str_replace(array('%v', '%s'), array($data['value'], $rData['symbol']), $data['template']);

        return $result;
    }

    /**
     * @param float $value
     * @param string $rule
     * @return string
     */
    public function noStyle($value, $rule)
    {
        $data = $this->_format($value, $rule);

        $result = str_replace(array('%v', '%s'), array($data['value'], ''), $data['template']);
        $result = trim($result);

        return $result;
    }

    public function html($value, $rule, $id, $origValue, $origRule)
    {
        $data  = $this->_format($value, $rule);
        $rData = $this->get($rule);

        $result = str_replace(array('%v', '%s'), array(
            '<span class="simpleType-value">' . $data['value'] . "</span>",
            '<span class="simpleType-symbol">' . $rData['symbol'] . "</span>",
        ), $data['template']);

        $result = '<span ' . $this->_htmlAttributes(array(
                'class'                      => array(
                    'simpleType',
                    'simpleType-block',
                    'simpleType-' . $this->_type,
                ),
                'data-simpleType-id'         => $id,
                'data-simpleType-value'      => $value,
                'data-simpleType-rule'       => $rule,
                'data-simpleType-orig-value' => $origValue,
                'data-simpleType-orig-rule'  => $origRule,
            )) . '>' . $result . '</span>';

        return $result;
    }

    public function htmlInput($value, $rule, $id, $origValue, $origRule, $inputName, $formatted)
    {
        $inputValue = $formatted ? $this->text($value, $rule) : $this->noStyle($value, $rule);

        return '<input ' . $this->_htmlAttributes(array(
            'value'                      => $inputValue,
            'name'                       => $inputName,
            'type'                       => 'text',
            'class'                      => array(
                'simpleType',
                'simpleType-' . $this->_type,
                'simpleType-input'
            ),
            'data-simpleType-id'         => $id,
            'data-simpleType-value'      => $value,
            'data-simpleType-rule'       => $rule,
            'data-simpleType-orig-value' => $origValue,
            'data-simpleType-orig-rule'  => $origRule,
        )) . ' />';
    }

    /**
     * @param $attributes
     * @return mixed
     */
    protected function _htmlAttributes($attributes)
    {
        $result = '';

        if (is_string($attributes)) {
            $result .= $attributes;

        } elseif (!empty($attributes)) {
            foreach ($attributes as $key => $param) {

                $param = (array)$param;
                $value = implode(' ', $param);
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                $value = trim($value);
                $result .= ' ' . $key . '="' . $value . '"';
            }
        }

        return trim($result);
    }

    /**
     * @param $value
     * @param $rule
     * @param null|int $roundValue
     * @param bool $roundType
     * @return float
     * @throws Exception
     */
    public function round($value, $rule, $roundValue = null, $roundType = false)
    {
        $format = $this->get($rule);

        if (!$roundType) {
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

        } else if (self::ROUND_NONE == $roundType) {
            $value = round($value, Formatter::ROUND_DEFAULT); // hack, because 123.400000001 !== 123.4

        } else {
            throw new Exception('Undefined round mode: "' . $roundType . '"');
        }

        return $value;
    }

    /**
     * Convert value to money format from config
     * @param float $value
     * @param string $rule
     * @return float
     */
    protected function _format($value, $rule)
    {
        $format = (array)$this->get($rule);
        $value  = !empty($value) ? $value : 0.0;

        $roundedValue = $this->round($value, $rule);
        $isPositive   = ($value >= 0);
        $valueStr     = number_format(abs($roundedValue), $format['num_decimals'], $format['decimal_sep'], $format['thousands_sep']);
        $template     = ($isPositive) ? $format['format_positive'] : $format['format_negative'];

        return array(
            'value'      => $valueStr,
            'template'   => $template,
            'isPositive' => $isPositive,
        );
    }

    /**
     * @param string $rule
     * @param array $newFormat
     */
    public function changeRule($rule, array $newFormat)
    {
        $oldFormat = $this->get($rule);

        $this->_rules[$rule] = array_merge($oldFormat, (array)$newFormat);
    }

    /**
     * @param string $rule
     * @param array $newFormat
     * @throws Exception
     */
    public function addRule($rule, array $newFormat = array())
    {
        if (!$rule) {
            throw new Exception('Empty rule name');
        }

        if (isset($this->_rules[$rule])) {
            throw new Exception('Format "' . $rule . '" already exists');
        }

        $this->_rules[$rule] = array_merge($this->_default, (array)$newFormat);
    }

    /**
     * @param string $rule
     */
    public function removeRule($rule)
    {
        if (isset($this->_rules[$rule])) {
            unset($this->_rules[$rule]);
        }
    }


}
