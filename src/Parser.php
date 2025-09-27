<?php

/**
 * JBZoo Toolbox - SimpleTypes.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/SimpleTypes
 */

declare(strict_types=1);

namespace JBZoo\SimpleTypes;

use function JBZoo\Utils\isStrEmpty;

final class Parser
{
    private string $default = '';
    private array  $rules   = [];

    public function __construct(string $default = '', array $ruleList = [])
    {
        $sortFunction = static fn (string $item1, string $item2): int => \strlen($item2) - \strlen($item1);

        \uksort($ruleList, $sortFunction);

        $this->rules   = $ruleList;
        $this->default = $default;
    }

    public function parse(mixed $data = null, ?string $forceRule = null): array
    {
        $rule = null;

        if (\is_array($data)) {
            $value = $data[0] ?? null;
            $rule  = $data[1] ?? null;

            return $this->parse($value, $rule);
        }

        $value   = \strtolower(\trim((string)$data));
        $aliases = $this->getCodeList();

        foreach ($aliases as $alias) {
            if (\str_contains($value, $alias)) {
                $rule  = $alias;
                $value = \str_ireplace($rule, '', $value);
                break;
            }
        }

        /** @phan-suppress-next-line PhanPartialTypeMismatchArgument */
        $value = self::cleanValue($value);
        $rule  = $this->checkRule($rule);

        if (!isStrEmpty($forceRule)) {
            $rule = $forceRule;
        }

        return [$value, $rule];
    }

    public function getCodeList(): array
    {
        return \array_keys($this->rules);
    }

    public function checkRule(?string $rule): string
    {
        $cleanRule = self::cleanRule($rule);

        if (isStrEmpty($cleanRule)) {
            return $this->default;
        }

        if (\array_key_exists($cleanRule, $this->rules)) {
            return $cleanRule;
        }

        throw new Exception("Undefined rule: {$cleanRule}");
    }

    public function addRule(string $newRule): void
    {
        $this->rules[$newRule] = $newRule;
    }

    public function removeRule(string $rule): void
    {
        if (\array_key_exists($rule, $this->rules)) {
            unset($this->rules[$rule]);
        }
    }

    public static function cleanValue(float|int|string|null $value): float
    {
        $result = \trim((string)$value);

        $result = (string)\preg_replace('#[^0-9-+eE,.]#', '', $result);

        if (\preg_match('#\d[eE][-+]\d#', $result) === 0) { // TODO: Remove exponential format
            $result = \str_replace(['e', 'E'], '', $result);
        }

        $result = (float)\str_replace(',', '.', $result);

        return \round($result, Formatter::ROUND_DEFAULT);
    }

    public static function cleanRule(?string $rule): string
    {
        return \strtolower(\trim((string)$rule));
    }
}
