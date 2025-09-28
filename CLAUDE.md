# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

JBZoo SimpleTypes is a universal PHP library for converting and manipulating values with units of measurement including money, weight, length, temperature, volume, area, and more. It provides smart parsing, arithmetic operations, formatting, and conversion between different units.

## Core Architecture

### Type System Structure
The library follows a consistent pattern with paired Config and Type classes:

**Core Classes:**
- `src/Type/AbstractType.php` - Base class for all measurement types
- `src/Config/AbstractConfig.php` - Base configuration class
- `src/Parser.php` - Smart input value parser
- `src/Formatter.php` - Output formatting and rendering

**Built-in Types:**
- `Money` - Currency conversion and arithmetic
- `Weight` - Mass measurements (kg, lb, g, etc.)
- `Length` - Distance measurements (km, mile, m, etc.)
- `Temperature` - Temp conversions (Celsius, Fahrenheit, Kelvin)
- `Volume` - Volume measurements (liter, gallon, etc.)
- `Area` - Area measurements (m², ft², etc.)
- `Time` - Time duration measurements
- `Info` - Digital storage (bytes, KB, MB, GB)
- `Degree` - Angular measurements

### Configuration System
Each type has a corresponding config class that defines:
- Default unit for parsing
- Conversion rates between units
- Formatting rules (symbols, decimal places, separators)
- Rounding behavior

### Smart Parser
The `Parser` class handles flexible input parsing:
- Extracts numbers from mixed strings
- Recognizes various decimal formats (comma/dot)
- Handles scientific notation
- Identifies unit symbols and names
- Supports negative values and percentages

## Common Commands

### Development
```bash
make update      # Install/update all dependencies
make autoload    # Dump optimized autoloader
```

### Testing
```bash
make test-all    # Run PHPUnit tests and all code style checks
make test        # Run PHPUnit tests only (alias for test-phpunit)
make codestyle   # Run all linting tools at once
```

### Individual Quality Assurance
```bash
make test-phpstan        # PHPStan static analysis
make test-psalm          # Psalm static analysis
make test-phpcs          # PHP Code Sniffer (PSR-12)
make test-phpcsfixer     # PHP-CS-Fixer style check
make test-phpcsfixer-fix # Auto-fix code style issues
make test-phpmd          # PHP Mess Detector
make test-phan           # Phan static analyzer
```

### Reports
```bash
make report-all          # Generate all reports
make report-phpmetrics   # PHP Metrics report
make report-pdepend      # PHP Depend analysis
```

## Usage Patterns

### Creating Type Objects
```php
// Various construction methods
$money = new Money('10 eur');
$weight = new Weight('1000'); // Uses default unit from config
$length = new Length('500 km');
$money = new Money('100500 usd', new ConfigMoney()); // Custom config
```

### Global Configuration
```php
// Set default configs for all objects of a type
Config::registerDefault('money', new ConfigMoney());
```

### Arithmetic Operations
All types support: `add()`, `subtract()`, `multiply()`, `division()`, `negative()`, `abs()`, `percent()`

### Method Chaining
Operations return the same object for chaining:
```php
$result = (new Money('10 usd'))
    ->add('5 eur')
    ->multiply(2)
    ->convert('rub');
```

### Output Formats
- `text()` - Formatted string with symbol
- `noStyle()` - Number only without symbol
- `html()` - HTML with data attributes for JavaScript
- `htmlInput()` - HTML input element
- `dump()` - Debug output with object ID

## Key Design Principles

### Immutable Operations
Most operations create new objects rather than modifying existing ones. Use `getClone()` when you need a copy.

### Flexible Input Parsing
The parser is very permissive and handles various input formats automatically.

### Type Safety
All arithmetic operations check type compatibility and handle unit conversions automatically.

### Debugging Support
Built-in logging system tracks all operations when debug mode is enabled in configs.

## File Structure

### Configuration Files
Configuration arrays are also available as separate files in `src/config/` for direct access to conversion rules.

### Test Structure
- `tests/` - PHPUnit tests following JBZoo test patterns
- Tests extend base classes from jbzoo/toolbox-dev
- Comprehensive coverage of parsing, arithmetic, and formatting

## Integration Notes

This library integrates with the JBZoo ecosystem:
- Uses `jbzoo/utils` for utility functions
- Follows JBZoo coding standards via `jbzoo/codestyle`
- Compatible with JBZoo's Makefile-based development workflow