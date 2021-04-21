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

require_once __DIR__ . '/vendor/autoload.php';

use JBZoo\SimpleTypes\Config\Config;
use JBZoo\SimpleTypes\Config\Length as ConfigLength;
use JBZoo\SimpleTypes\Config\Money as ConfigMoney;
use JBZoo\SimpleTypes\Config\Weight as ConfigWeight;
use JBZoo\SimpleTypes\Type\Length;
use JBZoo\SimpleTypes\Type\Money;
use JBZoo\SimpleTypes\Type\Weight;

// Set config object for all Money objects as default
Config::registerDefault('money', new ConfigMoney());
Config::registerDefault('weight', new ConfigWeight());
Config::registerDefault('length', new ConfigLength());

// Create Money object that represents 1 EUR
// Some different ways
$money = new Money('10 eur');
$money = new Weight('1000'); // Gram is default in the ConfigWeight class
$money = new Length('500 km');
$money = new Money('100500 usd', new ConfigMoney()); // my custom params only for that object

// Smart parser can find number, understand decimal symbol, trim, letter cases, e.t.c...
$money = new Money(' - 1 2 3 , 4 5 6 rub '); // Equals -123.456 rubles
$money = new Money('1.0e+18 EUR '); // Really huge number
$money = new Money('  EuR 3,50   ');
$money = new Money('usd');

// Different ways to use basic arithmetic
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

// Chaining method calls
$money = (new Money('4.95 usd'))
    ->add('10 usd')                         // $14.95
    ->subtract('2 eur')                     // $10.95
    ->negative()                            // -$10.95
    ->getClone()                            // copy of object is created
    ->division(5)                           // -$2.19
    ->multiply(10)                          // -$21.90
    ->convert('eur')                        // -10.95€ (For easy understanding we use 1 EUR = 2 USD)
    ->customFunc(function (Money $value) {  // custom handler
        $value
            ->add(new Money('600 rub'))     // 1.05€ (1 EUR = 50 RUB)
            ->add('-500%');                 // -4.2€
    })
    ->abs();                                // 4.2€

// show all actions (history)
$history = $money->logs();
echo $money->dump();

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
 *     [10] => Add "500 %"; New value = "-4.2 eur"
 *     [11] => <-- Function finished; New value = "-4.2 eur"
 *     [12] => Set positive/abs; New value = "4.2 eur"
 * )
 *
 * 4.2 eur; id=19
 */
