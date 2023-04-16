<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

trait CommonMultipleProviderTrait
{
    public static function iterableValueForSetMultipleProvider(): iterable
    {
        yield 'Array' => [
            ['key1' => 'value1', 'key2' => 'value2'],
        ];

        yield 'Traversable' => [
            new \ArrayIterator(['key1' => 'value1', 'key2' => 'value2']),
        ];

        yield 'Generator' => [
            (function () {
                yield 'key1' => 'value1';
                yield 'key2' => 'value2';
            })(),
        ];

        yield 'ArrayObject' => [
            new \ArrayObject(['key1' => 'value1', 'key2' => 'value2']),
        ];
    }

    public static function iterableValuesForGetMultipleAndUpdateMultipleProvider(): iterable
    {
        yield 'Array' => [
            ['key1', 'key2'],
            ['key1' => 'value1', 'key2' => 'value2'],
        ];

        yield 'Traversable' => [
            new \ArrayIterator(['key1', 'key2']),
            ['key1' => 'value1', 'key2' => 'value2'],
        ];

        yield 'Generator' => [
            (function () {
                yield 'key1';
                yield 'key2';
            })(),
            ['key1' => 'value1', 'key2' => 'value2'],
        ];

        yield 'ArrayObject' => [
            new \ArrayObject(['key1', 'key2']),
            ['key1' => 'value1', 'key2' => 'value2'],
        ];
    }
}