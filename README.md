# JBZoo / SimpleTypes

[![CI](https://github.com/JBZoo/SimpleTypes/actions/workflows/main.yml/badge.svg?branch=master)](https://github.com/JBZoo/SimpleTypes/actions/workflows/main.yml?query=branch%3Amaster)    [![Coverage Status](https://coveralls.io/repos/github/JBZoo/SimpleTypes/badge.svg?branch=master)](https://coveralls.io/github/JBZoo/SimpleTypes?branch=master)    [![Psalm Coverage](https://shepherd.dev/github/JBZoo/SimpleTypes/coverage.svg)](https://shepherd.dev/github/JBZoo/SimpleTypes)    [![Psalm Level](https://shepherd.dev/github/JBZoo/SimpleTypes/level.svg)](https://shepherd.dev/github/JBZoo/SimpleTypes)    [![CodeFactor](https://www.codefactor.io/repository/github/jbzoo/simpletypes/badge)](https://www.codefactor.io/repository/github/jbzoo/simpletypes/issues)    
[![Stable Version](https://poser.pugx.org/jbzoo/simpletypes/version)](https://packagist.org/packages/jbzoo/simpletypes/)    [![Total Downloads](https://poser.pugx.org/jbzoo/simpletypes/downloads)](https://packagist.org/packages/jbzoo/simpletypes/stats)    [![Dependents](https://poser.pugx.org/jbzoo/simpletypes/dependents)](https://packagist.org/packages/jbzoo/simpletypes/dependents?order_by=downloads)    [![GitHub License](https://img.shields.io/github/license/jbzoo/simpletypes)](https://github.com/JBZoo/SimpleTypes/blob/master/LICENSE)


The universal PHP library to convert any values and measures - money, weight, currency coverter, length and what ever you want ;)


## Installation
```
composer require jbzoo/simpletypes
```

## Examples

```php
use JBZoo\SimpleTypes\Config;
use JBZoo\SimpleTypes\Money;
use JBZoo\SimpleTypes\ConfigMoney;

// Set config object for all Money objects as default
Config::registerDefault('money', new ConfigMoney());

// Create any object, some different ways
$money  = new Money('10 eur');
$weight = new Weight('1000'); // Gram is default in the ConfigWeight class
$length = new Length('500 km');
$money  = new Money('100500 usd', new ConfigMoney()); // my custom params only for that object
```

## A lot of types are ready to use

SimpleTypes has such ready configurations like
  * [Area](https://github.com/JBZoo/SimpleTypes/blob/master/src/config/area.php)
  * [Degree](https://github.com/JBZoo/SimpleTypes/blob/master/src/config/degree.php) (geometry)
  * [Info](https://github.com/JBZoo/SimpleTypes/blob/master/src/config/info.php) (bytes, bits...)
  * [Length](https://github.com/JBZoo/SimpleTypes/blob/master/src/config/length.php)
  * [Money](https://github.com/JBZoo/SimpleTypes/blob/master/src/config/money.php) (Currency converter)
  * [Temperature](https://github.com/JBZoo/SimpleTypes/blob/master/src/config/temp.php) (Kelvin, Fahrenheit, Celsius and etc)
  * [Volume](https://github.com/JBZoo/SimpleTypes/blob/master/src/config/volume.php)
  * [Weight](https://github.com/JBZoo/SimpleTypes/blob/master/src/config/weight.php)

You can add your own type. It's really easy. See this page below.

### Smart and useful parser

SimpleTypes has really smart parser for all input values.
It can find number, understand any decimal symbols, trim, letter cases, e.t.c...
and it works really fast!

```php
$money = new Money(' - 1 2 3 , 4 5 6 rub ');    // Equals -123.456 rubles
$money = new Money('1.0e+18 EUR ');             // Really huge number. I'm rich! =)
$money = new Money('  EuR 3,50   ');
$money = new Money('usd');                      // Just object with usd rule
```

### Chaining method calls
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

## Basic arithmetic
Different ways to use basic arithmetic
```php
// example #1
$usd = new Money('10 usd');
$usd->add(new Money('10 eur'));

// example #2
$usd = (new Money('10 usd'))->add(new Money('10 eur'));

// example #3
$usd->add('10 eur');

// example #4
$usd->add('10'); // eur is default in the ConfigMoney

// example #5
$usd->add(['10', 'eur']);
```

SimpleTypes can
 * add
 * subtract
 * division
 * multiply
 * use custom functions (Closure)
 * negative / positibe / invert sign / abs
 * use percent
 * setting empty value
 * setting another value and rule
 * create clone
 * converting to any rules (currencies, units)
 * rounding
 * comparing
 * ... and others

## Compare values

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

And same examples but we will use smart parser
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

## Percent method
Simple way for count difference between two values
```php
$origPrice = new Money('100 usd');
$realPrice = new Money('40 eur');

$diff = $realPrice->percent($origPrice);
echo $diff->text(); // 80%

$discount = $realPrice->percent($origPrice, true); // revert flag added
echo $discount->text(); // 20%
```

## PHP magic methods

Safe serialize/unserialize
```php
$valBefore = $this->val('500 usd');
$valString = serialize($valBefore);
$valAfter  = unserialize($valString)->convert('eur');
$valBefore->compare($valAfter);// true
```

__toString() works like text() method
```php
$val = $this->val('500 usd');
echo $val; // "$500.00"
```

__invoke()
```php
$val = $this->val('10 eur');
// it's converting
$val('usd'); // so object now contains "20 usd" (1 eur = 2 usd)
// set new value and rule
$val('100 rub');
$val('100', 'uah');
```

## Different ways for output and rendering
### Only text

```php
$value = new Money('-50.666666 usd');
echo $value->text();            // "-$50.67"
echo $value->text('rub');       // "-1 266,67 руб." (output without changing inner state)
echo $value->noStyle('rub');    // "-1 266,67" (without symbol)
```

### Simple HTML rendering
```php
echo (new Money('-50.666666 usd'))->html('rub'); // render HTML, useful for JavaScript
```
Output (warping added just for clarity)
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

### HTML Input type[text]
```php
echo $value->htmlInput('rub', 'input-name-attr');
```
Output (warping added just for clarity)
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

**Notice:** Yes, we added a lot of data-attributes in the HTML code. It will be useful for JavaScript and converting without reload a page.


## Configuration of type

All configuration classes should be extended from Config class
For example, config for information
```php
/**
 * Class ConfigInfo
 * @package JBZoo\SimpleTypes
 */
class ConfigInfo extends Config
{
    /**
     * SimpleTypes uses it for converting and while parsing undefined values
     * @var string
     */
    public $default = 'byte';
    
    /**
     * To collect or not to collect logs for each object (need additional memory a little bit)
     * @var bool
     */
    public $isDebug = true;
    
    /**
     * Array of converting rules and output format
     * return array
     */
    public function getRules()
    {
        // key of array is alias for parser
        return array(
            'byte' => array(
                'rate' => 1 // Because 1 byte to byte is 1 =)))
            ),

            'kb'   => array(
                'symbol'          => 'KB',                     // symbol for output (->text(), ->html(), ...)
                'round_type'      => Formatter::ROUND_CLASSIC, // classic, float, ceil, none
                'round_value'     => Formatter::ROUND_DEFAULT, // Count of valuable number after decimal point for any arithmetic actions
                'num_decimals'    => '2',       // Sets the number of decimal points
                'decimal_sep'     => '.',       // Sets the separator for the decimal point.
                'thousands_sep'   => ' ',       // Sets the thousands separator.
                'format_positive' => '%v %s',   // %v - replace to rounded and formated (number_format()) value
                'format_negative' => '-%v %s',  // %s - replace to symbol
                'rate'            => 1024,      // How many bytes (default measure) in the 1 KB ?
            ),

            'mb'   => array( // Other params gets from $this->defaultParams variable
                'symbol' => 'MB',
                'rate'   => 1024 * 1024,
            ),

            'gb'   => array( // Other params gets from $this->defaultParams variable
                'symbol' => 'GB',
                'rate'   => 1024 * 1024 * 1024,
            ),

            'bit'  => array(
                'symbol' => 'Bit',
                'rate'   => function ($value, $to) { // Custom callback function for difficult conversion
                    if ($to == 'bit') {
                         return $value * 8;
                    }
                    return $value / 8;
                },
            ),
        );
    }
}
```

Usage example for our information type
```php
// create config object
$config = new ConfigInfo();

// you can register default config for all info-objects,
Config::registerDefault('info', $config);
$info1 = new Info('700 MB');
$info2 = new Info('1.4 GB');

// or add config object manually
$info1 = new Info('700 MB', $config);
$info2 = new Info('1.4 GB', $config);

// Well... some calculations
echo $info2->subtract($info1)->dump() . PHP_EOL;
echo $info2->convert('mb')->dump() . PHP_EOL;
print_r($info2->logs());
```

Output
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

### Debug information
Show list of all actions with object. For example, this is history for chaining code
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

Show real inner data without any formating and rounding. ID is unique number for SimpleType objects.
```php
echo $value->dump(); // "4.2 eur; id=19"
```

Get object id
```php
echo $value->getId(); // "19"
```
Show current value
```php
echo $value->val(); // "4.2"
```

Show current rule
```php
echo $value->rule(); // "eur"
```

## License
MIT
