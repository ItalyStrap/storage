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

### What is the purpose of this library?

The first idea is to have a common API for all the storage system in WordPress, like [WordPress Cache API](https://developer.wordpress.org/reference/classes/wp_object_cache/), [WordPress Transients API](https://developer.wordpress.org/apis/transients/), [WordPress Options API](https://developer.wordpress.org/apis/options/), [WordPress Theme Mods API](https://codex.wordpress.org/Theme_Modification_API) and so on.

### Why the Storage word for the library?

In this case the `Storage` word is used to refer to some data stored in a DB table or in a file as well as in memory like `array` or `object`, but in those case you may need to create your own class.
Now in this library you can find API for `Options` (or similar) and API for `Cache` (or similar), you may ask why :-D, the first reason is that I already have a library called [Cache](https://github.com/ItalyStrap/cache) used to implements the PSR-6 and PSR-16, so I don't want to duplicate the name, and the second reason group all the storage system you could find in WordPress in a single library.
Right now the interfaces used in this library are placed in another library called [Common](https://github.com/ItalyStrap/common) because I want to use them in other libraries.

### Why the OOP way?

With this library you can inject the storage system you need to use in your class instead of coupling your class to a specific storage system and simplify the testing of your class by mocking this library.

This API takes some concept from the [PSR-16](https://www.php-fig.org/psr/psr-16/), and also it could be applied to other kind of storage system in WordPress not only for the Transients, Options, Mods, etc., to naming a few you can use this API for metadata, post meta, user meta, and so on.

If you need a PSR-6 or PSR-16 implementation for WordPress transient and cache you can use the [ItalyStrap\Cache](https://github.com/ItalyStrap/cache) package and this library used as a driver.

Think of this like a wrapper around the WordPress Transients API, Options API, Mods API, etc. but with some differences.

### Differences with the WordPress Transients API, Options API, Mods API, etc.

The most important difference is the return value of the `::get()` method, in the WordPress Transients API, Cache API, Option API and so on the `\get_*()` functions return `false` if the result does not exist or has expired, in this API the `::get()` method return `null` if the result does not exist or has expired.

I'm not a fan of the `null` value but this adheres to the PSR-16 specification where no value means `null` and `false` could be a valid value.

### The expiration time

The second difference is that if you provide a `0` value as the expiration time it means you will store the value per `0` second and not `forever` like WordPress does.

Maybe you would ask why?

Because if you provide a `0` second to the expiration time you are telling to the library to store the value for `0` second and not forever (f**k), it does not make any sense to use the `0` value as `forever`.
If you want to store a value forever just use the `::set()` method without the expiration time at all or pass `null` as the expiration time, internally the library will set expiration time to `1 year`, and `1 year` should be enough for a `forever` value.

### The expiration time and the Cache API

Another important thing is that if you use the `Cache::class` and pass any value as the expiration time this will have no effect, because the `Cache::class` is a wrapper of the [WordPress Cache API](https://developer.wordpress.org/reference/classes/wp_object_cache/) and the default API will not persist any data.
If you install some other plugin that provide to you another implementation of the Cache API refer to the documentation of that plugin to know if it supports expiration time or not.

## Basic Usage

Remember that the maximum length of the key used for [transients](https://developer.wordpress.org/reference/functions/set_transient/) is <=172 characters, more characters will rise an Exception.

### Option API

```php
declare(strict_types=1);

namespace Your\Namespace;

use ItalyStrap\Storage\Option;

$option = new Option();

$option->set('my_option', 'my_value');

'my_value' === $option->get('my_option'); // true

$option->delete('my_option');

null === $option->get('my_option'); // true

$option->setMultiple([
    'option_1'	=> 'value_1',
    'option_2'	=> 'value_2',
]);

[
    'option_1'	=> 'value_1',
    'option_2'	=> 'value_2',
] === $option->getMultiple([
    'option_1',
    'option_2',
]); // true

$option->deleteMultiple([
    'option_1',
    'option_2',
]);

null === $option->get('option_1'); // true
null === $option->get('option_2'); // true
```

### Mods API

```php
declare(strict_types=1);

namespace Your\Namespace;

use ItalyStrap\Storage\Mods;

$mods = new Mod();

$mods->set('my_mod', 'my_value');

'my_value' === $mods->get('my_mod'); // true

$mods->delete('my_mod');

null === $mods->get('my_mod'); // true

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
]); // true

$mods->deleteMultiple([
    'mod_1',
    'mod_2',
]);

null === $mods->get('mod_1'); // true
null === $mods->get('mod_2'); // true

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

This is an example of how you can use the WordPress Transients API.

```php
if (false === ($special_data_to_save = \get_transient('special_data_to_save'))) {
    // It wasn't there, so regenerate the data and save the transient
    $special_data_to_save = ['some-key' => 'come value'];
    \set_transient('special_data_to_save', $special_data_to_save, 12 * HOUR_IN_SECONDS);
}
```

### Transient API

```php
declare(strict_types=1);

namespace Your\Namespace;

use ItalyStrap\Storage\Transient;

$transient = new Transient();

/**
 * Ttl value must be in seconds
 */
$transient->set('my_transient', 'my_value', 60);

'my_value' === $transient->get('my_transient'); // true

$transient->delete('my_transient');

null === $transient->get('my_transient'); // true

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
]); // true

$transient->deleteMultiple([
    'mod_1',
    'mod_2',
]);

null === $transient->get('mod_1'); // true
null === $transient->get('mod_2'); // true
```


```php
declare(strict_types=1);

namespace Your\Namespace;

use ItalyStrap\Storage\Transient;

$transient = new Transient();

if (false === ($special_data_to_save = $transient->get('special_data_to_save'))) {
    // It wasn't there, so regenerate the data and save the transient
    $special_data_to_save = ['some-key' => 'come value'];
    $transient->set('special_data_to_save', $special_data_to_save, 12 * HOUR_IN_SECONDS);
}
```

### Cache API

```php
declare(strict_types=1);

namespace Your\Namespace;

use ItalyStrap\Storage\Cache;

$cache = new Cache();

/**
 * Ttl value must be in seconds
 */
$cache->set('my_cache', 'my_value', 60);

'my_value' === $cache->get('my_cache'); // true

$cache->delete('my_cache');

null === $cache->get('my_cache'); // true

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
]); // true

$cache->deleteMultiple([
    'mod_1',
    'mod_2',
]);

null === $cache->get('mod_1'); // true
null === $cache->get('mod_2'); // true
```


```php
declare(strict_types=1);

namespace Your\Namespace;

use ItalyStrap\Storage\Cache;

$cache = new Cache();

if (false === ($special_data_to_save = $cache->get('special_data_to_save'))) {
    // It wasn't there, so regenerate the data and save the transient
    $special_data_to_save = ['some-key' => 'come value'];
    $cache->set('special_data_to_save', $special_data_to_save, 12 * HOUR_IN_SECONDS);
}
```

## Advanced Usage

## Contributing

All feedback / bug reports / pull requests are welcome.

## License

Copyright (c) 2019 Enea Overclokk, ItalyStrap

This code is licensed under the [MIT](LICENSE).

## Credits
