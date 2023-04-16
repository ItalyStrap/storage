<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\Storage\Cache;

trait CacheTestTrait
{
    use NormalizeTtlTestTrait;

    /**
     * @test
     */
    public function instanceOk(): void
    {
        $cache = $this->makeInstance();
        $this->assertInstanceOf(Cache::class, $cache);
    }

    /**
     * @test
     */
    public function setCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('foo', 'bar'));
        $this->assertSame('bar', \wp_cache_get('foo'));
        $this->assertSame('bar', $sut->get('foo'));
    }

    /**
     * @test
     */
    public function getCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertNull($sut->get('foo'));
        \wp_cache_set('foo', 'bar');
        $this->assertSame('bar', $sut->get('foo'));
    }

    /**
     * @test
     */
    public function testGetCacheWhenThaValueIsZero(): void
    {
        $sut = $this->makeInstance();
        \wp_cache_set('foo', 0);
        $this->assertSame(0, $sut->get('foo'));
    }

    /**
     * @test
     */
    public function updateCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('foo', 'bar'));
        $this->assertSame('bar', \wp_cache_get('foo'));
        $this->assertSame('bar', $sut->get('foo'));
        $this->assertTrue($sut->update('foo', 'baz'));
        $this->assertSame('baz', \wp_cache_get('foo'));
        $this->assertSame('baz', $sut->get('foo'));
    }

    /**
     * @test
     */
    public function deleteCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('foo', 'bar'));
        $this->assertSame('bar', \wp_cache_get('foo'));
        $this->assertSame('bar', $sut->get('foo'));
        $this->assertTrue($sut->delete('foo'));
        $this->assertFalse(\wp_cache_get('foo'));
        $this->assertNull($sut->get('foo'));
    }

    /**
     * @test
     */
    public function incrementCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('foo', 1));
        $this->assertSame(1, \wp_cache_get('foo'));
        $this->assertSame(1, $sut->get('foo'));
        $this->assertSame(2, $sut->increment('foo'));
        $this->assertSame(2, \wp_cache_get('foo'));
        $this->assertSame(2, $sut->get('foo'));
    }

    /**
     * @test
     */
    public function decrementCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('foo', 1));
        $this->assertSame(1, \wp_cache_get('foo'));
        $this->assertSame(1, $sut->get('foo'));
        $this->assertSame(0, $sut->decrement('foo'));
        $this->assertSame(0, \wp_cache_get('foo'));
        $this->assertSame(0, $sut->get('foo'));
    }

    /**
     * @test
     */
    public function clearCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('foo', 'bar'));
        $this->assertSame('bar', \wp_cache_get('foo'));
        $this->assertSame('bar', $sut->get('foo'));
        $this->assertTrue($sut->clear());
        $this->assertFalse(\wp_cache_get('foo'));
        $this->assertNull($sut->get('foo'));
    }

    public static function iterableValueForSetMultipleProvider()
    {
        yield 'array' => [
            ['foo' => 'bar', 'baz' => 'qux']
        ];

        yield 'Traversable' => [
            new \ArrayIterator(['foo' => 'bar', 'baz' => 'qux'])
        ];

        yield 'Generator' => [
            (function () {
                yield 'foo' => 'bar';
                yield 'baz' => 'qux';
            })(),
        ];

        yield 'ArrayObject' => [
            new \ArrayObject(['foo' => 'bar', 'baz' => 'qux'])
        ];
    }

    /**
     * @test
     * @dataProvider iterableValueForSetMultipleProvider
     */
    public function setMultiple(iterable $values): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple($values));
        $this->assertSame('bar', \wp_cache_get('foo'));
        $this->assertSame('bar', $sut->get('foo'));
        $this->assertSame('qux', \wp_cache_get('baz'));
        $this->assertSame('qux', $sut->get('baz'));
        $this->assertTrue(\wp_cache_get_multiple(['foo', 'baz']) === ['foo' => 'bar', 'baz' => 'qux']);
    }

    public static function iterableValuesForGetMultipleAndUpdateMultipleProvider()
    {
        yield 'array' => [
            ['foo', 'baz'],
            ['foo' => 'bar', 'baz' => 'qux'],
        ];

        yield 'Traversable' => [
            new \ArrayIterator(['foo', 'baz']),
            ['foo' => 'bar', 'baz' => 'qux'],
        ];

        yield 'Generator' => [
            (function () {
                yield 'foo';
                yield 'baz';
            })(),
            ['foo' => 'bar', 'baz' => 'qux'],
        ];

        yield 'ArrayObject' => [
            new \ArrayObject(['foo', 'baz']),
            ['foo' => 'bar', 'baz' => 'qux'],
        ];
    }

    /**
     * @test
     * @dataProvider iterableValuesForGetMultipleAndUpdateMultipleProvider
     */
    public function getMultiple(iterable $keys, iterable $expected = []): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple($expected));
        $actual = $sut->getMultiple($keys);

        foreach ($actual as $key => $value) {
            $this->assertSame($expected[$key], $value, '');
        }
    }

    /**
     * @test
     * @dataProvider iterableValuesForGetMultipleAndUpdateMultipleProvider
     */
    public function deleteMultiple(iterable $keys, iterable $expected = []): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple($expected));
        $this->assertTrue($sut->deleteMultiple($keys));
        $this->assertFalse(\wp_cache_get('foo'));
        $this->assertNull($sut->get('foo'));
        $this->assertFalse(\wp_cache_get('baz'));
        $this->assertNull($sut->get('baz'));
    }
}
