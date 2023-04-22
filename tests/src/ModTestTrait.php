<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\Storage\Mod;

trait ModTestTrait
{
    use CommonTrait;

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
    public function setMod(): void
    {
        $this->assertTrue($this->makeInstance()->set('key', 'value'));
        $this->assertSame('value', \get_theme_mod('key'));
    }

    /**
     * @test
     */
    public function getMod(): void
    {
        \set_theme_mod('key', 'value');
        $this->assertSame('value', $this->makeInstance()->get('key'));
    }

    /**
     * @test
     */
    public function updateMod(): void
    {
        $this->assertTrue($this->makeInstance()->update('key', 'value'));
        $this->assertSame('value', \get_theme_mod('key'));
    }

    /**
     * @test
     */
    public function deleteMod(): void
    {
        \set_theme_mod('key', 'value');
        $this->assertSame('value', \get_theme_mod('key'));
        $sut = $this->makeInstance();

        $this->assertTrue($sut->delete('key'));
        $this->assertFalse(\get_theme_mod('key'));
        $this->assertNull($sut->get('key'));
    }

    /**
     * @test
     */
    public function clearMod(): void
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
    public function deleteMultipleReturnFalse()
    {
        // Deleting a theme mod will always return true even if the mod doesn't exist
        $this->assertTrue($this->makeInstance()->deleteMultiple(['key1', 'key3']), '');
    }
}
