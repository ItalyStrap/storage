<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\StorageTests\CommonStoreMultipleTestsTrait;

trait OptionTestsTrait
{
    use CommonStoreMultipleTestsTrait;

    /**
     * @test
     */
    public function setOption(): void
    {
        $this->assertTrue($this->makeInstance()->set('key', 'value'));
        $this->assertSame('value', \get_option('key'));
    }

    /**
     * @test
     */
    public function getOption(): void
    {
        \add_option('key', 'value');
        $this->assertSame('value', $this->makeInstance()->get('key'));
    }

    /**
     * @test
     */
    public function updateOption(): void
    {
        $this->assertTrue($this->makeInstance()->update('key', 'value'));
        $this->assertSame('value', \get_option('key'));
    }

    /**
     * @test
     */
    public function deleteOption(): void
    {
        \add_option('key', 'value');
        $this->assertSame('value', \get_option('key'));
        $sut = $this->makeInstance();

        $this->assertTrue($sut->delete('key'));
        $this->assertFalse(\get_option('key'));
        $this->assertNull($sut->get('key'));
    }
}
