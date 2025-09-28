# JBZoo / SimpleTypes

[![CI](https://github.com/JBZoo/SimpleTypes/actions/workflows/main.yml/badge.svg?branch=master)](https://github.com/JBZoo/SimpleTypes/actions/workflows/main.yml?query=branch%3Amaster)
[![Coverage Status](https://coveralls.io/repos/github/JBZoo/SimpleTypes/badge.svg?branch=master)](https://coveralls.io/github/JBZoo/SimpleTypes?branch=master)
[![Psalm Coverage](https://shepherd.dev/github/JBZoo/SimpleTypes/coverage.svg)](https://shepherd.dev/github/JBZoo/SimpleTypes)
[![Psalm Level](https://shepherd.dev/github/JBZoo/SimpleTypes/level.svg)](https://shepherd.dev/github/JBZoo/SimpleTypes)
[![CodeFactor](https://www.codefactor.io/repository/github/jbzoo/simpletypes/badge)](https://www.codefactor.io/repository/github/jbzoo/simpletypes/issues)

[![Stable Version](https://poser.pugx.org/jbzoo/simpletypes/version)](https://packagist.org/packages/jbzoo/simpletypes/)
[![Total Downloads](https://poser.pugx.org/jbzoo/simpletypes/downloads)](https://packagist.org/packages/jbzoo/simpletypes/stats)
[![Dependents](https://poser.pugx.org/jbzoo/simpletypes/dependents)](https://packagist.org/packages/jbzoo/simpletypes/dependents?order_by=downloads)
[![GitHub License](https://img.shields.io/github/license/jbzoo/simpletypes)](LICENSE)

A universal PHP library for converting and manipulating values with units of measurement including money, weight, length, temperature, volume, area, and more. Features smart parsing, arithmetic operations, unit conversion, and flexible output formatting.


## Installation

```bash
composer require jbzoo/simpletypes
```

## Features

- **Smart Parser**: Handles various input formats, decimal symbols, whitespace, and case variations
- **Type System**: Built-in support for Money, Weight, Length, Temperature, Volume, Area, Time, Info, and Degree
- **Arithmetic Operations**: Add, subtract, multiply, divide with automatic unit conversion
- **Method Chaining**: Fluent interface for complex calculations
- **Flexible Output**: Text, HTML, and input field rendering with customizable formatting
- **Configuration**: Extensible configuration system for custom types and conversion rules
- **Debug Support**: Built-in logging and debugging capabilities

## Quick Start

```php
use JBZoo\SimpleTypes\Config;
use JBZoo\SimpleTypes\Money;
use JBZoo\SimpleTypes\ConfigMoney;

// Set config object for all Money objects as default
Config::registerDefault('money', new ConfigMoney());

// Create objects with automatic parsing
$money  = new Money('10 eur');
$weight = new Weight('1000'); // Gram is default unit
$length = new Length('500 km');
$money  = new Money('100500 usd', new ConfigMoney()); // Custom configuration
```

## Built-in Types

SimpleTypes comes with comprehensive configurations for common measurement types:

- **[Area](src/config/area.php)** - Square meters, feet, acres, hectares, etc.
- **[Degree](src/config/degree.php)** - Angular measurements (degrees, radians)
- **[Info](src/config/info.php)** - Digital storage (bytes, KB, MB, GB, TB)
- **[Length](src/config/length.php)** - Meters, feet, miles, kilometers, etc.
- **[Money](src/config/money.php)** - Currency conversion and formatting
- **[Temperature](src/config/temp.php)** - Celsius, Fahrenheit, Kelvin, Rankine
- **[Time](src/config/time.php)** - Seconds, minutes, hours, days, etc.
- **[Volume](src/config/volume.php)** - Liters, gallons, cubic meters, etc.
- **[Weight](src/config/weight.php)** - Grams, kilograms, pounds, ounces, etc.

Custom types can be easily created by extending the base configuration classes.

## Smart Parser

The parser automatically handles various input formats and edge cases:

```php
$money = new Money(' - 1 2 3 , 4 5 6 rub ');    // Parses to -123.456 rubles
$money = new Money('1.0e+18 EUR ');             // Scientific notation support
$money = new Money('  EuR 3,50   ');            // Case insensitive, handles whitespace
$money = new Money('usd');                      // Creates zero-value USD object
```

## Method Chaining

Perform complex calculations with fluent interface:
```php
$value = (new Money('4.95 usd'))
    ->add('10 usd')                         // $14.95
    ->subtract('2 eur')                     // $10.95
    ->negative()                            // -$10.95
    ->getClone()                            // copy of object is created
    ->division(5)                           // -$2.19
    ->multiply(10)                          // -$21.90
    ->convert('eur')                        // -10.95€ (For easy understanding we use 1 EUR = 2 USD)
    ->customFunc(function (Money $value) {  // sometimes we would like something more than plus/minus ;)
        $value
            ->add(new Money('600 rub'))     // 1.05€ (1 EUR = 50 RUB)
            ->add('-500%');                 // -4.2€
    })
    ->abs();                                // 4.2€
```

## Arithmetic Operations

Multiple ways to perform calculations:
```php
// Using objects
$usd = new Money('10 usd');
$usd->add(new Money('10 eur'));

// Method chaining
$usd = (new Money('10 usd'))->add(new Money('10 eur'));

// String input with automatic parsing
$usd->add('10 eur');

// Using default unit from configuration
$usd->add('10');

// Array format
$usd->add(['10', 'eur']);
```

**Supported Operations:**
- Addition and subtraction with automatic unit conversion
- Multiplication and division
- Custom functions via closures
- Sign operations (negative, positive, absolute value)
- Percentage calculations
- Value and unit modifications
- Object cloning
- Unit/currency conversion
- Rounding and formatting
- Comparison operations
- Serialization support

## Value Comparison

Compare values with automatic unit conversion:

```php
$kg = new Weight('1 kg'); // one kilogram
$lb = new Weight('2 lb'); // two pounds

var_dump($kg->compare($lb));            // false ("==" by default)
var_dump($kg->compare($lb, '=='));      // false
var_dump($kg->compare($lb, '<'));       // false
var_dump($kg->compare($lb, '<='));      // false
var_dump($kg->compare($lb, '>'));       // true
var_dump($kg->compare($lb, '>='));      // true
```

Using string input with smart parsing:
```php
$kg = new Weight('1 kg');
$lb = new Weight('2 lb');

var_dump($kg->compare('1000 g'));       // true
var_dump($kg->compare('2 lb', '=='));   // false
var_dump($kg->compare('2 lb', '<'));    // false
var_dump($kg->compare('2 lb', '<='));   // false
var_dump($kg->compare('2 lb', '>'));    // true
var_dump($kg->compare('2 lb', '>='));   // true
```

## Percentage Calculations

Calculate percentage differences between values:
```php
$origPrice = new Money('100 usd');
$realPrice = new Money('40 eur');

$diff = $realPrice->percent($origPrice);
echo $diff->text(); // 80%

$discount = $realPrice->percent($origPrice, true); // revert flag added
echo $discount->text(); // 20%
```

## Magic Methods

**Serialization Support:**
```php
$valBefore = $this->val('500 usd');
$valString = serialize($valBefore);
$valAfter  = unserialize($valString)->convert('eur');
$valBefore->compare($valAfter);// true
```

**String Conversion:**
```php
$val = $this->val('500 usd');
echo $val; // "$500.00"
```

**Object Invocation:**
```php
$val = $this->val('10 eur');
// Convert to different currency
$val('usd'); // Converts to USD using exchange rates
// Set new value and currency
$val('100 rub');
$val('100', 'uah');
```

## Output Formatting

### Text Output

```php
$value = new Money('-50.666666 usd');
echo $value->text();            // "-$50.67"
echo $value->text('rub');       // "-1 266,67 руб." (convert for display only)
echo $value->noStyle('rub');    // "-1 266,67" (number without currency symbol)
```

### HTML Output
```php
echo (new Money('-50.666666 usd'))->html('rub'); // HTML with data attributes for JavaScript
```
Generated HTML (formatted for readability):
```php
<span
    class="simpleType simpleType-block simpleType-money"
    data-simpleType-id="1"
    data-simpleType-value="-1266.66665"
    data-simpleType-rule="rub"
    data-simpleType-orig-value="-50.666666"
    data-simpleType-orig-rule="usd">
        -<span class="simpleType-value">1 266,67</span>
        <span class="simpleType-symbol">руб.</span>
</span>
```

### HTML Input Fields
```php
echo $value->htmlInput('rub', 'input-name-attr');
```
Generated HTML (formatted for readability):
```html
<input
    value="-1 266,67"
    name="input-name-attr"
    type="text"
    class="simpleType simpleType-money simpleType-input"
    data-simpleType-id="1"
    data-simpleType-value="-1266.66665"
    data-simpleType-rule="rub"
    data-simpleType-orig-value="-50.666666"
    data-simpleType-orig-rule="usd"
/>
```

**Note:** The HTML output includes extensive data attributes to enable client-side JavaScript operations without server round-trips.


## Custom Configuration

Create custom types by extending the base configuration class. Here's an example for digital storage:
```php
/**
 * Example: Digital storage configuration
 */
class ConfigInfo extends Config
{
    // Default unit for parsing
    public $default = 'byte';

    // Enable debug logging
    public $isDebug = true;

    // Define conversion rules and formatting
    public function getRules()
    {
        return [
            'byte' => [
                'rate' => 1 // Base unit
            ],

            'kb' => [
                'symbol'          => 'KB',                     // Display symbol
                'round_type'      => Formatter::ROUND_CLASSIC, // Rounding method
                'round_value'     => Formatter::ROUND_DEFAULT, // Decimal precision
                'num_decimals'    => '2',                      // Decimal places
                'decimal_sep'     => '.',                      // Decimal separator
                'thousands_sep'   => ' ',                      // Thousands separator
                'format_positive' => '%v %s',                  // Positive format (%v=value, %s=symbol)
                'format_negative' => '-%v %s',                 // Negative format
                'rate'            => 1024,                     // Conversion rate from base unit
            ],

            'mb' => [
                'symbol' => 'MB',
                'rate'   => 1024 * 1024,
            ],

            'gb' => [
                'symbol' => 'GB',
                'rate'   => 1024 * 1024 * 1024,
            ],

            'bit' => [
                'symbol' => 'Bit',
                'rate'   => function ($value, $to) { // Custom conversion function
                    return ($to === 'bit') ? $value * 8 : $value / 8;
                },
            ],
        ];
    }
}
```

### Using Custom Configuration
```php
// Create configuration instance
$config = new ConfigInfo();

// Register as default for all Info objects
Config::registerDefault('info', $config);
$info1 = new Info('700 MB');
$info2 = new Info('1.4 GB');

// Or pass configuration directly
$info1 = new Info('700 MB', $config);
$info2 = new Info('1.4 GB', $config);

// Perform calculations
echo $info2->subtract($info1)->dump() . PHP_EOL;
echo $info2->convert('mb')->dump() . PHP_EOL;
print_r($info2->logs());
```

**Output:**
```
0.71640625 gb; id=4
733.6 mb; id=4
Array
(
    [0] => Id=4 has just created; dump="1.4 gb"
    [1] => Subtract "700 mb"; New value = "0.71640625 gb"
    [2] => Converted "gb"=>"mb"; New value = "733.6 mb"; 1 gb = 1024 mb
)
```

## Debug and Introspection

### Operation History

Track all operations performed on an object:
```php
print_r($value->logs());

/**
 * Array
 * (
 *     [0]  => Id=16 has just created; dump="4.95 usd"
 *     [1]  => Add "10 usd"; New value = "14.95 usd"
 *     [2]  => Subtract "2 eur"; New value = "10.95 usd"
 *     [3]  => Set negative; New value = "-10.95 usd"
 *     [4]  => Cloned from id=16 and created new with id=19; dump=-10.95 usd
 *     [5]  => Division with "5"; New value = "-2.19 usd"
 *     [6]  => Multiply with "10"; New value = "-21.9 usd"
 *     [7]  => Converted "usd"=>"eur"; New value = "-10.95 eur"; 1 usd = 0.5 eur
 *     [8]  => --> Function start
 *     [9]  => Add "600 rub"; New value = "1.05 eur"
 *     [10] => Add "-500 %"; New value = "-4.2 eur"
 *     [11] => <-- Function finished; New value = "-4.2 eur"
 *     [12] => Set positive/abs; New value = "4.2 eur"
 * )
 */
```

### Internal State

Access raw internal data without formatting:
```php
echo $value->dump(); // "4.2 eur; id=19"
```

### Object Identification
```php
echo $value->getId(); // "19"
```
### Value Access
```php
echo $value->val(); // "4.2"
```

### Unit Access
```php
echo $value->rule(); // "eur"
```

## Requirements

- PHP 8.2 or higher
- jbzoo/utils ^7.3

## License

MIT License. See [LICENSE](LICENSE) file for details.
