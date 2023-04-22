<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\Storage\Cache;

trait CacheTestTrait
{
    use NormalizeTtlTestTrait, CommonMultipleProviderTrait, CommonTrait;

    /**
     * @test
     */
    public function instanceOk(): void
    {
        $this->assertInstanceOf(Cache::class, $this->makeInstance());
    }

    /**
     * @test
     */
    public function setCache(): void
    {
        $this->assertTrue($this->makeInstance()->set('key', 'value'));
        $this->assertSame('value', \wp_cache_get('key'));
    }

    /**
     * @test
     */
    public function getCache(): void
    {
        \wp_cache_set('key', 'value');
        $this->assertSame('value', $this->makeInstance()->get('key'));
    }

    /**
     * @test
     */
    public function getCacheWhenValueIsZero(): void
    {
        \wp_cache_set('key', 0);
        $this->assertSame(0, $this->makeInstance()->get('key'), '');
    }

    /**
     * @test
     */
    public function updateCache(): void
    {
        $this->assertTrue($this->makeInstance()->update('key', 'value'));
        $this->assertSame('value', \wp_cache_get('key'));
    }

    /**
     * @test
     */
    public function deleteCache(): void
    {
        \wp_cache_set('key', 'value');
        $this->assertSame('value', \wp_cache_get('key'));
        $sut = $this->makeInstance();

        $this->assertTrue($sut->delete('key'));
        $this->assertFalse(\wp_cache_get('key'));
        $this->assertNull($sut->get('key'));
    }

    /**
     * @test
     */
    public function clearCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('key', 'value'));
        $this->assertSame('value', $sut->get('key'));
        $this->assertTrue($sut->clear());
        $this->assertNull($sut->get('key'));
    }

    /**
     * @test
     */
    public function incrementCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('key', 1));
        $this->assertSame(1, $sut->get('key'));
        $this->assertSame(2, $sut->increment('key'));
        $this->assertSame(2, $sut->get('key'));
    }

    /**
     * @test
     */
    public function decrementCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('key', 1));
        $this->assertSame(1, $sut->get('key'));
        $this->assertSame(0, $sut->decrement('key'));
        $this->assertSame(0, $sut->get('key'));
    }
}
