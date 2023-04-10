# ItalyStrap Storage API

[![Test Application](https://github.com/ItalyStrap/storage/actions/workflows/test.yml/badge.svg)](https://github.com/ItalyStrap/storage/actions/workflows/test.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/italystrap/storage.svg)](https://packagist.org/packages/italystrap/storage)
[![Total Downloads](https://img.shields.io/packagist/dt/italystrap/storage.svg)](https://packagist.org/packages/italystrap/storage)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/italystrap/storage.svg)](https://packagist.org/packages/italystrap/storage)
[![License](https://img.shields.io/packagist/l/italystrap/storage.svg)](https://packagist.org/packages/italystrap/storage)
![PHP from Packagist](https://img.shields.io/packagist/php-v/italystrap/storage)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FItalyStrap%2Fstorage%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/ItalyStrap/storage/master)

Storage API for WordPress the OOP way

## Table Of Contents

* [Installation](#installation)
* [Basic Usage](#basic-usage)
* [Advanced Usage](#advanced-usage)
* [Contributing](#contributing)
* [License](#license)

## Installation

The best way to use this package is through Composer:

```CMD
composer require italystrap/storage
```
This package adheres to the [SemVer](http://semver.org/) specification and will be fully backward compatible between minor versions.

## Introduction

What is the purpose of this library?

The first idea is to have a common API for all the storage system in WordPress, like Transients, Options, Mods, etc.

In this case the Storage word is used to refer to some data stored in a DB table or in a file, also if you want in memory like array or object.

With this library you can inject the storage system you need to use in your class instead of coupling your class to a specific storage system and simplify the testing of your class.

This API takes some concept from the [PSR-16](https://www.php-fig.org/psr/psr-16/), and also it could be applied to other kind of storage system in WordPress not only for the Transients, Options, Mods, etc.

To naming a few you can use this API for metadata, post meta, user meta, etc.

If you need a PSR-6 or PSR-16 implementation for WordPress transient and cache you can use the [ItalyStrap\Cache](https://github.com/ItalyStrap/cache) package.

Think of this like a wrapper around the WordPress Transients API, Options API, Mods API, etc. but with some differences.

The most important difference is the return value of the `get()` method, in the WordPress Transients API, Cache API, Option API and so on the `get_*()` function return `false` if the result does not exist or has expired, in this API the `get()` method return `null` if the result does not exist or has expired.

I'm not a fan of the `null` value but this adhere to the PSR-16 specification.

## Basic Usage

Remember that the maximum length of the key is 172 characters, more characters will rise an Exception.

### Option API

```php
use ItalyStrap\Storage\Option;

$option = new Option();

$option->set('my_option', 'my_value');

'my_value' === $option->get('my_option');

$option->delete('my_option');

null === $option->get('my_option');

$option->setMultiple([
    'option_1'	=> 'value_1',
    'option_2'	=> 'value_2',
]);

[
    'option_1'	=> 'value_1',
    'option_2'	=> 'value_2',
] === $option->getMultiple( [
    'option_1',
    'option_2',
] );

$option->deleteMultiple( [
    'option_1',
    'option_2',
] );

null === $option->get('option_1');
null === $option->get('option_2');
```

### Mods API

```php

use ItalyStrap\Storage\Mods;

$mods = new Mod();

$mods->set('my_mod', 'my_value');

'my_value' === $mods->get('my_mod');

$mods->delete('my_mod');

null === $mods->get('my_mod');

$mods->setMultiple([
    'mod_1'	=> 'value_1',
    'mod_2'	=> 'value_2',
]);

[
    'mod_1'	=> 'value_1',
    'mod_2'	=> 'value_2',
] === $mods->getMultiple([
    'mod_1',
    'mod_2',
]);

$mods->deleteMultiple([
    'mod_1',
    'mod_2',
]);

null === $mods->get('mod_1');
null === $mods->get('mod_2');

$mods->clear();
```

From [WordPress Transients API docs](https://codex.wordpress.org/Transients_API)

### Timer constants

```php
const MINUTE_IN_SECONDS  = 60; // (seconds)
const HOUR_IN_SECONDS    = 60 * MINUTE_IN_SECONDS;
const DAY_IN_SECONDS     = 24 * HOUR_IN_SECONDS;
const WEEK_IN_SECONDS    = 7 * DAY_IN_SECONDS;
const MONTH_IN_SECONDS   = 30 * DAY_IN_SECONDS;
const YEAR_IN_SECONDS    = 365 * DAY_IN_SECONDS;
```

### Common usage with WordPress Transients API

```php
if ( false === ( $special_data_to_save = \get_transient( 'special_data_to_save' ) ) ) {
    // It wasn't there, so regenerate the data and save the transient
    $special_data_to_save = ['some-key' => 'come value'];
    \set_transient( 'special_data_to_save', $special_data_to_save, 12 * HOUR_IN_SECONDS );
}
```

### Transient API

```php

use ItalyStrap\Storage\Transient;

$transient = new Transient();

/**
 * Ttl value must be in seconds
 */
$transient->set('my_transient', 'my_value', 60);

'my_value' === $transient->get('my_transient');

$transient->delete('my_transient');

null === $transient->get('my_transient');

$transient->setMultiple([
    'mod_1'	=> 'value_1',
    'mod_2'	=> 'value_2',
], 60);

[
    'mod_1'	=> 'value_1',
    'mod_2'	=> 'value_2',
] === $transient->getMultiple([
    'mod_1',
    'mod_2',
]);

$transient->deleteMultiple([
    'mod_1',
    'mod_2',
]);

null === $transient->get('mod_1');
null === $transient->get('mod_2');
```

### Cache API

```php

use ItalyStrap\Storage\Cache;

$cache = new Cache();

/**
 * Ttl value must be in seconds
 */
$cache->set('my_cache', 'my_value', 60);

'my_value' === $cache->get('my_cache');

$cache->delete('my_cache');

null === $cache->get('my_cache');

$cache->setMultiple([
    'mod_1'	=> 'value_1',
    'mod_2'	=> 'value_2',
], 60);

[
    'mod_1'	=> 'value_1',
    'mod_2'	=> 'value_2',
] === $cache->getMultiple([
    'mod_1',
    'mod_2',
]);

$cache->deleteMultiple([
    'mod_1',
    'mod_2',
]);

null === $cache->get('mod_1');
null === $cache->get('mod_2');
```

## Advanced Usage

## Contributing

All feedback / bug reports / pull requests are welcome.

## License

Copyright (c) 2019 Enea Overclokk, ItalyStrap

This code is licensed under the [MIT](LICENSE).

## Credits
