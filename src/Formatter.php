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

declare(strict_types=1);

namespace JBZoo\SimpleTypes;

/**
 * Class Formatter
 * @package JBZoo\SimpleTypes
 */
class Formatter
{
    public const ROUND_DEFAULT = 8;

    public const ROUND_NONE    = 'none';
    public const ROUND_CEIL    = 'ceil';
    public const ROUND_FLOOR   = 'floor';
    public const ROUND_CLASSIC = 'classic';

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var string|null
     */
    protected $type = '';

    /**
     * @var array
     */
    protected $default = [];

    /**
     * @param array       $rules
     * @param array       $default
     * @param string|null $type
     */
    public function __construct(array $rules = [], array $default = [], ?string $type = null)
    {
        $this->type = $type;
        $this->default = $default;

        // prepare rules
        $this->rules = array_change_key_case($rules, CASE_LOWER);

        foreach ($this->rules as $key => $item) {
            $this->rules[$key] = array_merge($default, (array)$item);
        }
    }

    /**
     * @param string $rule
     * @return array
     */
    public function get(string $rule): array
    {
        if (array_key_exists($rule, $this->rules)) {
            return (array)$this->rules[$rule];
        }

        throw new Exception("Undefined rule: '{$rule}'");
    }

    /**
     * @param bool $keysOnly
     * @return array
     */
    public function getList(bool $keysOnly = false): array
    {
        if ($keysOnly) {
            $keys = array_keys($this->rules);
            $values = array_keys($this->rules);
            return array_combine($keys, $values) ?: [];
        }

        return $this->rules;
    }

    /**
     * @param float  $value
     * @param string $rule
     * @param bool   $showSymbol
     * @return string
     */
    public function text(float $value, string $rule, bool $showSymbol = true): string
    {
        $data = $this->format($value, $rule);
        $rData = $this->get($rule);
        $symbol = $showSymbol ? $rData['symbol'] : '';

        $result = str_replace(
            ['%v', '%s'],
            [$data['value'], $symbol],
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
     */
    public function html(array $current, array $orig, array $params): string
    {
        $data = $this->format($current['value'], $current['rule']);
        $rData = $this->get($current['rule']);

        $result = str_replace(
            ['%v', '%s'],
            [
                "<span class=\"simpleType-value\">{$data['value']}</span>",
                "<span class=\"simpleType-symbol\">{$rData['symbol']}</span>",
            ],
            $data['template']
        );

        return '<span ' . self::htmlAttributes([
                'class'                      => [
                    'simpleType',
                    'simpleType-block',
                    "simpleType-{$this->type}",
                ],
                'data-simpleType-id'         => $params['id'],
                'data-simpleType-value'      => $current['value'],
                'data-simpleType-rule'       => $current['rule'],
                'data-simpleType-orig-value' => $orig['value'],
                'data-simpleType-orig-rule'  => $orig['rule'],
            ]) . ">{$result}</span>";
    }

    /**
     * @param array $current
     * @param array $orig
     * @param array $params
     * @return string
     */
    public function htmlInput(array $current, array $orig, array $params): string
    {
        $inputValue = $params['formatted']
            ? $this->text($current['value'], $current['rule'])
            : $this->text($current['value'], $current['rule'], false);

        return '<input ' . self::htmlAttributes([
                'value'                      => $inputValue,
                'name'                       => $params['name'],
                'type'                       => 'text',
                'class'                      => [
                    'simpleType',
                    "simpleType-{$this->type}",
                    'simpleType-input',
                ],
                'data-simpleType-id'         => $params['id'],
                'data-simpleType-value'      => $current['value'],
                'data-simpleType-rule'       => $current['rule'],
                'data-simpleType-orig-value' => $orig['value'],
                'data-simpleType-orig-rule'  => $orig['rule'],
            ]) . ' />';
    }

    /**
     * @param array $attributes
     * @return string
     */
    public static function htmlAttributes(array $attributes): string
    {
        $result = '';

        if (count($attributes)) {
            foreach ($attributes as $key => $param) {
                $value = implode(' ', (array)$param);
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                $value = trim($value);
                $result .= " {$key}=\"{$value}\"";
            }
        }

        return trim($result);
    }

    /**
     * @param float  $value
     * @param string $rule
     * @param array  $params
     * @return float
     */
    public function round(float $value, string $rule, array $params = []): float
    {
        $format = $this->get($rule);

        // prepare params
        $params = array_merge(['roundType' => null, 'roundValue' => null], $params);

        // get vars
        $roundType = $params['roundType'];
        $roundValue = $params['roundValue'];

        if (!$roundType) {
            $roundType = array_key_exists('round_type', $format) ? $format['round_type'] : self::ROUND_NONE;
        }

        if (null === $roundValue) {
            $roundValue = array_key_exists('round_value', $format) ? $format['round_value'] : self::ROUND_DEFAULT;
        }

        $roundValue = (int)$roundValue;

        if (self::ROUND_CEIL === $roundType) {
            $base = 10 ** $roundValue;
            $value = ceil($value * $base) / $base;
        } elseif (self::ROUND_CLASSIC === $roundType) {
            $value = round($value, $roundValue);
        } elseif (self::ROUND_FLOOR === $roundType) {
            $base = 10 ** $roundValue;
            $value = floor($value * $base) / $base;
        } elseif (self::ROUND_NONE === $roundType) {
            $value = round($value, self::ROUND_DEFAULT); // hack, because 123.400000001 !== 123.4
        } else {
            throw new Exception("Undefined round mode: '{$roundType}'");
        }

        return $value;
    }

    /**
     * Convert value to money format from config
     * @param float  $value
     * @param string $rule
     * @return array
     */
    protected function format(float $value, string $rule): array
    {
        $format = $this->get($rule);

        $roundedValue = $this->round($value, $rule);
        $isPositive = ($value >= 0);
        $valueStr = number_format(
            abs($roundedValue),
            (int)($format['num_decimals'] ?? 0),
            (string)($format['decimal_sep'] ?? '.'),
            (string)($format['thousands_sep'] ?? '')
        );

        $template = $isPositive ? $format['format_positive'] : $format['format_negative'];

        return [
            'value'      => $valueStr,
            'template'   => $template,
            'isPositive' => $isPositive,
        ];
    }

    /**
     * @param string $rule
     * @param array  $newFormat
     */
    public function changeRule(string $rule, array $newFormat): void
    {
        $oldFormat = $this->get($rule);

        $this->rules[$rule] = array_merge($oldFormat, $newFormat);
    }

    /**
     * @param string $rule
     * @param array  $newFormat
     */
    public function addRule(string $rule, array $newFormat = []): void
    {
        if (!$rule) {
            throw new Exception('Empty rule name');
        }

        if (array_key_exists($rule, $this->rules)) {
            throw new Exception("Format '{$rule}' already exists");
        }

        $this->rules[$rule] = array_merge($this->default, $newFormat);
    }

    /**
     * @param string $rule
     * @return bool
     */
    public function removeRule(string $rule): bool
    {
        if (array_key_exists($rule, $this->rules)) {
            unset($this->rules[$rule]);
            return true;
        }

        return false;
    }
}
