<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\Storage\Cache;

trait CacheTestTrait
{
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
    public function getCache(): void
    {
        $sut = $this->makeInstance();
        $this->assertFalse($sut->get('foo'));
        \wp_cache_set('foo', 'bar');
        $this->assertSame('bar', $sut->get('foo'));
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
        $this->assertFalse($sut->get('foo'));
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
        $this->assertFalse($sut->get('foo'));
    }

    /**
     * @test
     */
    public function setMultiple(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple(['foo' => 'bar', 'baz' => 'qux']));
        $this->assertSame('bar', \wp_cache_get('foo'));
        $this->assertSame('bar', $sut->get('foo'));
        $this->assertSame('qux', \wp_cache_get('baz'));
        $this->assertSame('qux', $sut->get('baz'));
        $this->assertTrue(\wp_cache_get_multiple(['foo', 'baz']) === ['foo' => 'bar', 'baz' => 'qux']);
    }

    /**
     * @test
     */
    public function getMultiple(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple(['foo' => 'bar', 'baz' => 'qux']));
        $actual = $sut->getMultiple(['foo', 'baz']);
        $this->assertSame(['foo' => 'bar', 'baz' => 'qux'], \iterator_to_array($actual));
    }

    /**
     * @test
     */
    public function deleteMultiple(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple(['foo' => 'bar', 'baz' => 'qux']));
        $this->assertTrue($sut->deleteMultiple(['foo', 'baz']));
        $this->assertFalse(\wp_cache_get('foo'));
        $this->assertFalse($sut->get('foo'));
        $this->assertFalse(\wp_cache_get('baz'));
        $this->assertFalse($sut->get('baz'));
    }
}
