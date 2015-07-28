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
    protected $rules = array();

    /**
     * @var string|null
     */
    protected $type = '';

    /**
     * @var array
     */
    protected $default = array();

    /**
     * @param array  $rules
     * @param array  $default
     * @param string $type
     */
    public function __construct(array $rules = array(), array $default = array(), $type = null)
    {
        $this->type    = $type;
        $this->default = $default;

        // prepare rules
        $this->rules = array_change_key_case((array)$rules, CASE_LOWER);
        array_walk($this->rules, function (&$item, $key, $default) {
            $item = array_merge($default, (array)$item);
        }, $this->default);
    }

    /**
     * @param string $rule
     * @return array
     * @throws Exception
     */
    public function get($rule)
    {
        if (array_key_exists($rule, $this->rules)) {
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
     * @param bool   $showSymbol
     * @return mixed|string
     * @throws Exception
     */
    public function text($value, $rule, $showSymbol = true)
    {
        $data   = $this->format($value, $rule);
        $rData  = $this->get($rule);
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
     * @param array $current
     * @param array $orig
     * @param array $params
     * @return string
     * @throws Exception
     */
    public function html($current, $orig, $params)
    {
        $data  = $this->format($current['value'], $current['rule']);
        $rData = $this->get($current['rule']);

        $result = str_replace(
            array('%v', '%s'),
            array(
                '<span class="simpleType-value">' . $data['value'] . '</span>',
                '<span class="simpleType-symbol">' . $rData['symbol'] . '</span>',
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
                'data-simpleType-id'         => $params['id'],
                'data-simpleType-value'      => $current['value'],
                'data-simpleType-rule'       => $current['rule'],
                'data-simpleType-orig-value' => $orig['value'],
                'data-simpleType-orig-rule'  => $orig['rule'],
            )
        ) . '>' . $result . '</span>';
    }

    /**
     * @param array $current
     * @param array $orig
     * @param array $params
     * @return string
     * @throws Exception
     */
    public function htmlInput($current, $orig, $params)
    {
        $inputValue = $params['formatted']
            ? $this->text($current['value'], $current['rule'])
            : $this->text($current['value'], $current['rule'], false);

        return '<input ' . $this->htmlAttributes(
            array(
                'value'                      => $inputValue,
                'name'                       => $params['name'],
                'type'                       => 'text',
                'class'                      => array(
                    'simpleType',
                    'simpleType-' . $this->type,
                    'simpleType-input'
                ),
                'data-simpleType-id'         => $params['id'],
                'data-simpleType-value'      => $current['value'],
                'data-simpleType-rule'       => $current['rule'],
                'data-simpleType-orig-value' => $orig['value'],
                'data-simpleType-orig-rule'  => $orig['rule'],
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

        $attributes = (array)$attributes;

        if (count($attributes)) {
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

        if (!$roundType) {
            $roundType = array_key_exists('round_type', $format) ? $format['round_type'] : self::ROUND_NONE;
        }

        if (null === $roundValue) {
            $roundValue = array_key_exists('round_value', $format) ? $format['round_value'] : self::ROUND_DEFAULT;
        }

        $roundValue = (int)$roundValue;

        if (self::ROUND_CEIL === $roundType) {
            $base  = pow(10, $roundValue);
            $value = ceil($value * $base) / $base;

        } elseif (self::ROUND_CLASSIC === $roundType) {
            $value = round($value, $roundValue);

        } elseif (self::ROUND_FLOOR === $roundType) {
            $base  = pow(10, $roundValue);
            $value = floor($value * $base) / $base;

        } elseif (self::ROUND_NONE === $roundType) {
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
     * @throws Exception
     */
    protected function format($value, $rule)
    {
        $format = (array)$this->get($rule);
        $value  = (float)$value;

        $roundedValue = $this->round($value, $rule);
        $isPositive   = ($value >= 0);
        $valueStr     = number_format(
            abs($roundedValue),
            $format['num_decimals'],
            $format['decimal_sep'],
            $format['thousands_sep']
        );

        $template = $isPositive ? $format['format_positive'] : $format['format_negative'];

        return array(
            'value'      => $valueStr,
            'template'   => $template,
            'isPositive' => $isPositive,
        );
    }

    /**
     * @param string $rule
     * @param array  $newFormat
     * @throws Exception
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

        if (array_key_exists($rule, $this->rules)) {
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
        if (array_key_exists($rule, $this->rules)) {
            unset($this->rules[$rule]);
        }
    }
}
