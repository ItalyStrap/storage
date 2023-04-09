<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\Storage\Mod;

trait ModTestTrait
{
    /**
     * @test
     */
    public function instanceOk(): void
    {
        $this->assertInstanceOf(Mod::class, $this->makeInstance());
    }

    /**
     * @test
     */
    public function getMod(): void
    {
        $sut = $this->makeInstance();
        $this->assertNull($sut->get('foo'));
        \set_theme_mod('foo', 'bar');
        $this->assertSame('bar', $sut->get('foo'));
    }

    /**
     * @test
     */
    public function setMod(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('foo', 'bar'));
        $this->assertSame('bar', $sut->get('foo'));
        $this->assertSame('bar', \get_theme_mod('foo'));
    }

    /**
     * @test
     */
    public function updateMod(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->update('foo', 'bar'));
        $this->assertSame('bar', $sut->get('foo'));
        $this->assertSame('bar', \get_theme_mod('foo'));
    }

    /**
     * @test
     */
    public function deleteMod(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->delete('foo'));
        $this->assertNull($sut->get('foo'));
        $this->assertFalse(\get_theme_mod('foo'));
    }

    /**
     * @test
     */
    public function clearMod(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('foo', 'bar'));
        $this->assertSame('bar', $sut->get('foo'));
        $this->assertSame('bar', \get_theme_mod('foo'));
        $this->assertTrue($sut->clear());
        $this->assertNull($sut->get('foo'));
        $this->assertFalse(\get_theme_mod('foo'));
    }

    /**
     * @test
     */
    public function setMultipleMod(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple(['foo' => 'bar', 'bar' => 'foo']));
        $this->assertSame('bar', $sut->get('foo'));
        $this->assertSame('bar', \get_theme_mod('foo'));
        $this->assertSame('foo', $sut->get('bar'));
        $this->assertSame('foo', \get_theme_mod('bar'));
    }

    /**
     * @test
     */
    public function getMultipleMod(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple(['foo' => 'bar', 'bar' => 'foo']));
        $actual = $sut->getMultiple(['foo', 'bar']);
        $this->assertSame(['foo' => 'bar', 'bar' => 'foo'], \iterator_to_array($actual));
        $this->assertSame('bar', \get_theme_mod('foo'));
        $this->assertSame('foo', \get_theme_mod('bar'));
    }

    /**
     * @test
     */
    public function deleteMultipleMod(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple(['foo' => 'bar', 'bar' => 'foo']));
        $this->assertTrue($sut->deleteMultiple(['foo', 'bar']));
        $this->assertNull($sut->get('foo'));
        $this->assertFalse(\get_theme_mod('foo'));
        $this->assertNull($sut->get('bar'));
        $this->assertFalse(\get_theme_mod('bar'));
    }

    /**
     * @test
     */
    public function clear(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple(['foo' => 'bar', 'bar' => 'foo']));
        $this->assertTrue($sut->clear());
        $this->assertNull($sut->get('foo'));
        $this->assertFalse(\get_theme_mod('foo'));
        $this->assertNull($sut->get('bar'));
        $this->assertFalse(\get_theme_mod('bar'));
    }
}
