# SimpleTypes

### The universal PHP library to convert any values and measures

[![License](https://poser.pugx.org/smetdenis/simpletypes/license)](https://packagist.org/packages/smetdenis/simpletypes)  [![Latest Stable Version](https://poser.pugx.org/smetdenis/simpletypes/v/stable)](https://packagist.org/packages/smetdenis/simpletypes)  [![Build Status](https://travis-ci.org/smetdenis/SimpleTypes.svg?branch=master)](https://travis-ci.org/smetdenis/SimpleTypes)  [![Coverage Status](https://coveralls.io/repos/smetdenis/SimpleTypes/badge.svg)](https://coveralls.io/r/smetdenis/SimpleTypes)  [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/smetdenis/SimpleTypes/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/smetdenis/SimpleTypes/?branch=master)  [![Code Climate](https://codeclimate.com/github/smetdenis/SimpleTypes/badges/gpa.svg)](https://codeclimate.com/github/smetdenis/SimpleTypes)  
[![Dependency Status](https://www.versioneye.com/user/projects/5596cc726166340021000010/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5596cc726166340021000010) 
[![HHVM Status](http://hhvm.h4cc.de/badge/smetdenis/simpletypes.svg)](http://hhvm.h4cc.de/package/smetdenis/simpletypes)  [![SensioLabsInsight](https://insight.sensiolabs.com/projects/03303b96-02dc-4e6e-b1ca-ac87e5f4ca9a/mini.png)](https://insight.sensiolabs.com/projects/03303b96-02dc-4e6e-b1ca-ac87e5f4ca9a)

## Installation

Simply add a dependency on `smetdenis/simpletypes` to your project's `composer.json` file if you use [Composer](http://getcomposer.org/) to manage the dependencies of your project.

Here is a minimal example of a `composer.json` file that just defines a dependency on Money:

    {
        "require": {
            "smetdenis/simpletypes": "1.*"
        }
    }


## Usage Examples

```php
use SmetDenis\SimpleTypes\Config;
use SmetDenis\SimpleTypes\Money;
use SmetDenis\SimpleTypes\ConfigMoney;

// Set config object for all Money objects as default
Config::registerDefault('money', new ConfigMoney());

// Create Money object that represents 1 EUR
// Some different ways
$money = new Money('10 eur');
$money = new Weight('1000'); // Gram is default in the ConfigWeight class
$money = new Length('500 km');
$money = new Money('100500 usd', new ConfigMoney()); // my custom params only for that object
```

### Smart and useful parser
SimpleTypes has smart parser that can find number, understand decimal symbol, trim, lettercases, e.t.c...

```php
$money = new Money(' - 1 2 3 , 4 5 6 rub '); // Equals -123.456 rubles
$money = new Money('1.0e+18 EUR '); // Really huge number
$money = new Money('  EuR 3,50   ');
$money = new Money('usd');
```

### Basic arithmetic
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

### Chaining method calls
```php
$value = (new Money('4.95 usd'))
    ->add('10 usd')// $14.95
    ->subtract('2 eur')// $10.95
    ->negative()// -$10.95
    ->getClone()// copy of object is created
    ->division(5)// -$2.19
    ->multiply(10)// -$21.90
    ->convert('eur')// -10.95ˆ (For easy understanding we use 1 EUR = 2 USD)
    ->customFunc(function (Money $value) {
        $value
            ->add(new Money('600 rub'))// 1.05ˆ (1 EUR = 50 RUB)
            ->add('-500%');// -4.2ˆ
    })
    ->abs() // 4.2ˆ
```

### Debug information
Show history of all actions with object
```php
print_r($value->logs());

/** 
 * You will see something like that
 * 
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

Show real inner data 
```php
echo $value->dump(); // "4.2 eur; id=19"
```


