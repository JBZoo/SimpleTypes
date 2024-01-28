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

namespace JBZoo\SimpleTypes\Type;

use JBZoo\SimpleTypes\Config\AbstractConfig;
use JBZoo\SimpleTypes\Exception;
use JBZoo\SimpleTypes\Formatter;
use JBZoo\SimpleTypes\Parser;
use JBZoo\Utils\Str;

use function JBZoo\Utils\isStrEmpty;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 *
 * @property string $value
 * @property string $rule
 */
abstract class AbstractType
{
    protected static int $counter = 0;

    protected int       $uniqueId      = 0;
    protected string    $type          = '';
    protected float|int $internalValue = 0;
    protected string    $internalRule  = '';
    protected string    $default       = '';
    protected array     $logs          = [];
    protected bool      $isDebug       = false;
    protected Parser    $parser;
    protected Formatter $formatter;

    public function __construct(null|array|float|int|string $value = null, ?AbstractConfig $config = null)
    {
        $this->prepareObject($value, $config);
    }

    public function __toString()
    {
        $this->log('__toString() called');

        return $this->text();
    }

    /**
     * Serialize.
     * @return array
     */
    public function __sleep()
    {
        $result   = [];
        $reflect  = new \ReflectionClass($this);
        $propList = $reflect->getProperties();

        foreach ($propList as $prop) {
            if ($prop->isStatic()) {
                continue;
            }
            $result[] = $prop->name;
        }

        $this->log('Serialized');

        return $result;
    }

    /**
     * Wake up after serialize.
     */
    public function __wakeup(): void
    {
        $this->log('--> wakeup start');
        $this->prepareObject($this->dump(false));
        $this->log('<-- Wakeup finish');
    }

    /**
     * Clone object.
     */
    public function __clone()
    {
        self::$counter++;
        $recentId       = $this->uniqueId;
        $this->uniqueId = self::$counter;

        $this->log(
            "Cloned from id='{$recentId}' and created new with id='{$this->uniqueId}'; dump=" . $this->dump(false),
        );
    }

    /**
     * @return float|string
     */
    public function __get(string $name)
    {
        $name = \strtolower($name);

        if ($name === 'value') {
            return $this->val();
        }

        if ($name === 'rule') {
            return $this->getRule();
        }

        throw new Exception("{$this->type}: Undefined __get() called: '{$name}'");
    }

    /**
     * @noinspection MagicMethodsValidityInspection
     */
    public function __set(string $name, mixed $value): void
    {
        if ($name === 'value') {
            $this->set([$value]);
        } elseif ($name === 'rule') {
            $this->convert($value);
        } else {
            throw new Exception("{$this->type}: Undefined __set() called: '{$name}' = '{$value}'");
        }
    }

    /**
     * Experimental! Methods aliases.
     * @deprecated
     */
    public function __call(string $name, array $arguments): mixed
    {
        $name = \strtolower($name);
        if ($name === 'value') {
            return \call_user_func_array([$this, 'val'], $arguments);
        }

        if ($name === 'plus') {
            return \call_user_func_array([$this, 'add'], $arguments);
        }

        if ($name === 'minus') {
            return \call_user_func_array([$this, 'subtract'], $arguments);
        }

        throw new Exception("{$this->type}: Called undefined method: '{$name}'");
    }

    public function __invoke(): self
    {
        $args         = \func_get_args();
        $argsCount    = \count($args);
        $shortArgList = 1;
        $fullArgList  = 2;

        if ($argsCount === 0) {
            $this->error('Undefined arguments');
        } elseif ($argsCount === $shortArgList) {
            $rules = $this->formatter->getList();

            if (\array_key_exists($args[0], $rules)) {
                return $this->convert($args[0]);
            }

            return $this->set($args[0]);
        } elseif ($argsCount === $fullArgList) {
            return $this->set([$args[0], $args[1]]);
        }

        throw new Exception("{$this->type}: Too many arguments");
    }

    public function getId(): int
    {
        return $this->uniqueId;
    }

    public function val(?string $rule = null): float
    {
        $rule = Parser::cleanRule($rule);

        if ($rule !== $this->internalRule && !isStrEmpty($rule)) {
            return $this->customConvert($rule);
        }

        return $this->internalValue;
    }

    public function text(?string $rule = null): string
    {
        $rule = !isStrEmpty($rule) ? $this->parser->checkRule($rule) : $this->internalRule;
        $this->log("Formatted output in '{$rule}' as 'text'");

        return $this->formatter->text($this->val($rule), $rule);
    }

    public function noStyle(?string $rule = null): string
    {
        $rule = !isStrEmpty($rule) ? $this->parser->checkRule($rule) : $this->internalRule;
        $this->log("Formatted output in '{$rule}' as 'noStyle'");

        return $this->formatter->text($this->val($rule), $rule, false);
    }

    public function html(?string $rule = null): string
    {
        $rule = !isStrEmpty($rule) ? $this->parser->checkRule($rule) : $this->internalRule;
        $this->log("Formatted output in '{$rule}' as 'html'");

        return $this->formatter->html(
            ['value' => $this->val($rule), 'rule' => $rule],
            ['value' => $this->internalValue, 'rule' => $this->internalRule],
            ['id'    => $this->uniqueId],
        );
    }

    public function htmlInput(?string $rule = null, ?string $name = null, bool $formatted = false): string
    {
        $rule = !isStrEmpty($rule) ? $this->parser->checkRule($rule) : $this->internalRule;
        $this->log("Formatted output in '{$rule}' as 'input'");

        return $this->formatter->htmlInput(
            ['value' => $this->val($rule), 'rule' => $rule],
            ['value' => $this->internalValue, 'rule' => $this->internalRule],
            ['id'    => $this->uniqueId, 'name' => $name, 'formatted' => $formatted],
        );
    }

    public function isRule(string $rule): bool
    {
        $rule = $this->parser->checkRule($rule);

        return $rule === $this->internalRule;
    }

    public function getRule(): string
    {
        return $this->internalRule;
    }

    public function isEmpty(): bool
    {
        return (float)$this->internalValue === 0.0;
    }

    public function isPositive(): bool
    {
        return $this->internalValue > 0;
    }

    public function isNegative(): bool
    {
        return $this->internalValue < 0;
    }

    public function getRules(): array
    {
        return $this->formatter->getList();
    }

    public function data(bool $toString = false): array|string
    {
        $data = [(string)$this->val(), $this->getRule()];

        return $toString ? \implode(' ', $data) : $data;
    }

    public function getClone(): self
    {
        return clone $this;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function compare(
        null|array|float|int|self|string $value,
        string $mode = '==',
        int $round = Formatter::ROUND_DEFAULT,
    ): bool {
        // prepare value
        $value = $this->getValidValue($value);

        $mode = \trim($mode);
        $mode = \in_array($mode, ['=', '==', '==='], true) ? '==' : $mode;

        $val1 = \round($this->val($this->internalRule), $round);
        $val2 = \round($value->val($this->internalRule), $round);

        $this->log(
            "Compared '{$this->dump(false)}' {$mode} '{$value->dump(false)}' // {$val1} {$mode} {$val2}, r={$round}",
        );

        if ($mode === '==') {
            return $val1 === $val2;
        }

        if ($mode === '!=' || $mode === '!==') {
            return $val1 !== $val2;
        }

        if ($mode === '<') {
            return $val1 < $val2;
        }

        if ($mode === '>') {
            return $val1 > $val2;
        }

        if ($mode === '<=') {
            return $val1 <= $val2;
        }

        if ($mode === '>=') {
            return $val1 >= $val2;
        }

        throw new Exception("{$this->type}: Undefined compare mode: {$mode}");
    }

    public function setEmpty(bool $getClone = false): self
    {
        return $this->modifier(0.0, 'Set empty', $getClone);
    }

    public function add(null|array|float|int|self|string $value, bool $getClone = false): self
    {
        return $this->customAdd($value, $getClone);
    }

    public function subtract(null|array|float|int|self|string $value, bool $getClone = false): self
    {
        return $this->customAdd($value, $getClone, true);
    }

    public function convert(string $newRule, bool $getClone = false): self
    {
        if ($newRule === '') {
            $newRule = $this->internalRule;
        }

        $newRule = $this->parser->checkRule($newRule);

        $obj = $getClone ? clone $this : $this;

        if ($newRule !== $obj->internalRule) {
            $obj->internalValue = $obj->customConvert($newRule, true);
            $obj->internalRule  = $newRule;
        }

        return $obj;
    }

    public function invert(bool $getClone = false): self
    {
        $logMess = 'Invert sign';
        if ($this->internalValue > 0) {
            $newValue = -1 * $this->internalValue;
        } elseif ($this->internalValue < 0) {
            $newValue = \abs((float)$this->internalValue);
        } else {
            $newValue = $this->internalValue;
        }

        return $this->modifier($newValue, $logMess, $getClone);
    }

    public function positive(bool $getClone = false): self
    {
        return $this->modifier(\abs((float)$this->internalValue), 'Set positive/abs', $getClone);
    }

    public function negative(bool $getClone = false): self
    {
        return $this->modifier(-1 * \abs((float)$this->internalValue), 'Set negative', $getClone);
    }

    public function abs(bool $getClone = false): self
    {
        return $this->positive($getClone);
    }

    public function multiply(float $number, bool $getClone = false): self
    {
        $multiplier = Parser::cleanValue($number);
        $newValue   = $multiplier * $this->internalValue;

        return $this->modifier($newValue, "Multiply with '{$multiplier}'", $getClone);
    }

    public function division(float $number, bool $getClone = false): self
    {
        $divider = Parser::cleanValue($number);

        return $this->modifier($this->internalValue / $divider, "Division with '{$divider}'", $getClone);
    }

    public function percent(self|string $value, bool $revert = false): self
    {
        $value = $this->getValidValue($value);

        $percent = 0.0;
        if (!$this->isEmpty() && !$value->isEmpty()) {
            $percent = ($this->internalValue / $value->val($this->internalRule)) * 100;
        }

        if ($revert) {
            $percent = 100 - $percent;
        }

        $result = $this->getValidValue("{$percent}%");
        $this->log("Calculate percent; '{$this->dump(false)}' / {$value->dump(false)} = {$result->dump(false)}");

        return $result;
    }

    public function customFunc(\Closure $function, bool $getClone = false): self
    {
        $this->log('--> Function start');
        $function($this);

        return $this->modifier($this->internalValue, '<-- Function finished', $getClone);
    }

    public function set(null|array|float|int|self|string $value, bool $getClone = false): self
    {
        $value = $this->getValidValue($value);

        $this->internalValue = $value->val();
        $this->internalRule  = $value->getRule();

        return $this->modifier($this->internalValue, "Set new value = '{$this->dump(false)}'", $getClone);
    }

    public function round(int $roundValue, string $mode = Formatter::ROUND_CLASSIC): self
    {
        $oldValue = $this->internalValue;
        $newValue = $this->formatter->round($this->internalValue, $this->internalRule, [
            'roundValue' => $roundValue,
            'roundType'  => $mode,
        ]);

        $this->log("Rounded (size={$roundValue}; type={$mode}) '{$oldValue}' => {$newValue}");

        $this->internalValue = $newValue;

        return $this;
    }

    public function getValidValue(null|array|float|int|self|string $value): self
    {
        if ($value instanceof self) {
            $thisClass = \strtolower(static::class);
            $valClass  = \strtolower($value::class);
            if ($thisClass !== $valClass) {
                throw new Exception("{$this->type}: No valid object type given: {$valClass}");
            }
        } else {
            /**
             * @psalm-suppress UnsafeInstantiation
             * @phpstan-ignore-next-line
             */
            $value = new static($value, $this->getConfig());
        }

        return $value;
    }

    public function error(string $message): void
    {
        $this->log($message);
        throw new Exception("{$this->type}: {$message}");
    }

    public function dump(bool $showId = true): string
    {
        $uniqueId = $showId ? "; id={$this->uniqueId}" : '';

        return "{$this->internalValue} {$this->internalRule}{$uniqueId}";
    }

    public function log(string $message): void
    {
        if ($this->isDebug) {
            $this->logs[] = $message;
        }
    }

    public function logs(): array
    {
        return $this->logs;
    }

    public function changeRule(string $rule, array $newFormat): self
    {
        $rule = Parser::cleanRule($rule);
        $this->formatter->changeRule($rule, $newFormat);
        $this->log("The rule '{$rule}' changed");

        return $this;
    }

    public function addRule(string $rule, array $newFormat = []): self
    {
        $form = $this->formatter;
        $rule = Parser::cleanRule($rule);
        $form->addRule($rule, $newFormat);
        $this->parser->addRule($rule);
        $this->log("The rule '{$rule}' added");

        return $this;
    }

    public function removeRule(string $rule): self
    {
        $rule = Parser::cleanRule($rule);
        $this->formatter->removeRule($rule);
        $this->parser->removeRule($rule);
        $this->log("The rule '{$rule}' removed");

        return $this;
    }

    public function getRuleData(string $rule): array
    {
        $rule = Parser::cleanRule($rule);

        return $this->formatter->get($rule);
    }

    protected function getConfig(?AbstractConfig $config = null): ?AbstractConfig
    {
        $defaultConfig = AbstractConfig::getDefault($this->type);

        $config ??= $defaultConfig;

        // Hack for getValidValue method
        if ($defaultConfig === null && $config !== null) {
            AbstractConfig::registerDefault($this->type, $config);
        }

        return $config;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function customConvert(string $rule, bool $addToLog = false): float
    {
        $from   = $this->parser->checkRule($this->internalRule);
        $target = $this->parser->checkRule($rule);

        $ruleTo   = $this->formatter->get($target);
        $ruleFrom = $this->formatter->get($from);
        $ruleDef  = $this->formatter->get($this->default);

        $log = "'{$from}'=>'{$target}'";

        $result = $this->internalValue;
        if ($from !== $target) {
            if (\is_callable($ruleTo['rate']) || \is_callable($ruleFrom['rate'])) {
                if (\is_callable($ruleFrom['rate'])) {
                    $defNorm = $ruleFrom['rate']($this->internalValue, $this->default, $from);
                } else {
                    $defNorm = $this->internalValue * $ruleFrom['rate'] * $ruleDef['rate'];
                }

                if (\is_callable($ruleTo['rate'])) {
                    $result = $ruleTo['rate']($defNorm, $target, $this->default);
                } else {
                    $result = $defNorm / $ruleTo['rate'];
                }
            } else {
                $defNorm = $this->internalValue * $ruleFrom['rate'] * $ruleDef['rate'];
                $result  = $defNorm / $ruleTo['rate'];
            }

            if ($this->isDebug && $addToLog) {
                $message = [
                    "Converted {$log};",
                    "New value = {$result} {$target};",
                    \is_callable($ruleTo['rate']) ? "func({$from})" : "{$ruleTo['rate']} {$from}",
                    '=',
                    \is_callable($ruleFrom['rate']) ? "func({$target})" : "{$ruleFrom['rate']} {$target}",
                ];

                $this->log(\implode(' ', $message));
            }
        }

        return $result;
    }

    protected function customAdd(
        null|array|float|int|self|string $value,
        bool $getClone = false,
        bool $isSubtract = false,
    ): self {
        $value = $this->getValidValue($value);

        $addValue = 0;

        if ($this->internalRule === '%') {
            if ($value->getRule() === '%') {
                $addValue = $value->val();
            } else {
                $this->error("Impossible add '{$value->dump(false)}' to '{$this->dump(false)}'");
            }
        } elseif ($value->getRule() !== '%') {
            $addValue = $value->val($this->internalRule);
        } else {
            $addValue = $this->internalValue * $value->val() / 100;
        }

        if ($isSubtract) {
            $addValue *= -1;
        }

        $newValue = $this->internalValue + $addValue;
        $logMess  = ($isSubtract ? 'Subtract' : 'Add') . " '{$value->dump(false)}'";

        return $this->modifier($newValue, $logMess, $getClone);
    }

    protected function modifier(float $newValue, ?string $logMessage = null, bool $getClone = false): self
    {
        if ($getClone) {
            $clone = $this->getClone();

            $clone->internalValue = $newValue;
            $clone->log("{$logMessage}; New value = '{$clone->dump(false)}'");

            return $clone;
        }

        $this->internalValue = $newValue;
        $this->log("{$logMessage}; New value = '{$this->dump(false)}'");

        return $this;
    }

    private function prepareObject(null|array|float|int|string $value = null, ?AbstractConfig $config = null): void
    {
        // $this->type = Str::class \strtolower(\str_replace(__NAMESPACE__ . '\\', '', static::class));
        $this->type = Str::getClassName(static::class, true) ?? 'UndefinedType';

        // get custom or global config
        $config = $this->getConfig($config);
        if ($config !== null) {
            // debug flag (for logging)
            $this->isDebug = $config->isDebug;

            // set default rule
            $this->default = \strtolower(\trim($config->default));
            if (isStrEmpty($this->default)) {
                $this->error('Default rule cannot be empty!');
            }

            // create formatter helper
            $this->formatter = new Formatter($config->getRules(), $config->defaultParams, $this->type);
        } else {
            $this->formatter = new Formatter();
        }

        // check that default rule
        $rules = $this->formatter->getList(true);
        if (!\array_key_exists($this->default, $rules) || \count($rules) === 0) {
            throw new Exception("{$this->type}: Default rule not found!");
        }

        // create parser helper
        $this->parser = new Parser($this->default, $rules);

        // parse data
        [$this->internalValue, $this->internalRule] = $this->parser->parse($value);

        // count unique id
        self::$counter++;
        $this->uniqueId = self::$counter;

        // success log
        $this->log("Id={$this->uniqueId} has just created; dump='{$this->dump(false)}'");
    }
}
