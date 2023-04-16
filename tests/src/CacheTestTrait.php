<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\Storage\Cache;

trait CacheTestTrait
{
    use NormalizeTtlTestTrait, CommonMultipleProviderTrait;

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
        $this->assertTrue($sut->set('key', 'value'));
        $this->assertSame('value', \wp_cache_get('key'));
        $this->assertSame('value', $sut->get('key'));
    }

    /**
     * @test
     */
    public function getCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertNull($sut->get('key'));
        \wp_cache_set('key', 'value');
        $this->assertSame('value', $sut->get('key'));
    }

    /**
     * @test
     */
    public function getTransientDefaultValueIfKeyDoesNotExists(): void
    {
        $this->assertSame('default', $this->makeInstance()->get('key', 'default'), '');
    }

    /**
     * @test
     */
    public function testGetCacheWhenThaValueIsZero(): void
    {
        $sut = $this->makeInstance();
        \wp_cache_set('key', 0);
        $this->assertSame(0, $sut->get('key'));
    }

    /**
     * @test
     */
    public function updateCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('key', 'value'));
        $this->assertSame('value', \wp_cache_get('key'));
        $this->assertSame('value', $sut->get('key'));
        $this->assertTrue($sut->update('key', 'value'));
        $this->assertSame('value', \wp_cache_get('key'));
        $this->assertSame('value', $sut->get('key'));
    }

    /**
     * @test
     */
    public function deleteCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('key', 'value'));
        $this->assertSame('value', \wp_cache_get('key'));
        $this->assertSame('value', $sut->get('key'));
        $this->assertTrue($sut->delete('key'));
        $this->assertFalse(\wp_cache_get('key'));
        $this->assertNull($sut->get('key'));
    }

    /**
     * @test
     */
    public function incrementCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('key', 1));
        $this->assertSame(1, \wp_cache_get('key'));
        $this->assertSame(1, $sut->get('key'));
        $this->assertSame(2, $sut->increment('key'));
        $this->assertSame(2, \wp_cache_get('key'));
        $this->assertSame(2, $sut->get('key'));
    }

    /**
     * @test
     */
    public function decrementCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('key', 1));
        $this->assertSame(1, \wp_cache_get('key'));
        $this->assertSame(1, $sut->get('key'));
        $this->assertSame(0, $sut->decrement('key'));
        $this->assertSame(0, \wp_cache_get('key'));
        $this->assertSame(0, $sut->get('key'));
    }

    /**
     * @test
     */
    public function clearCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('key', 'value'));
        $this->assertSame('value', \wp_cache_get('key'));
        $this->assertSame('value', $sut->get('key'));
        $this->assertTrue($sut->clear());
        $this->assertFalse(\wp_cache_get('key'));
        $this->assertNull($sut->get('key'));
    }

    /**
     * @test
     * @dataProvider iterableValueForSetMultipleProvider
     */
    public function setMultiple(iterable $values): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple($values));
        $this->assertSame('value1', \wp_cache_get('key1'));
        $this->assertSame('value1', $sut->get('key1'));
        $this->assertSame('value2', \wp_cache_get('key2'));
        $this->assertSame('value2', $sut->get('key2'));
        $this->assertTrue(\wp_cache_get_multiple(['key1', 'key2']) === ['key1' => 'value1', 'key2' => 'value2']);
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
        $this->assertFalse(\wp_cache_get(\array_key_first($expected)));
        $this->assertNull($sut->get(\array_key_first($expected)));
        $this->assertFalse(\wp_cache_get(\array_key_last($expected)));
        $this->assertNull($sut->get(\array_key_last($expected)));
    }
}
