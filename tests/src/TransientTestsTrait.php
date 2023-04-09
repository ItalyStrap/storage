<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\Storage\Transient;

trait TransientTestsTrait
{
    public function testInstanceOk(): void
    {
        $this->assertInstanceOf(Transient::class, $this->makeInstance());
    }

    /**
     * @test
     */
    public function setTransient()
    {
        $this->makeInstance()->set('key', 'value');
        $this->assertSame('value', \get_transient('key'), '');
    }

    /**
     * @test
     */
    public function getTransient()
    {
        \set_transient('key', 'value');
        $this->assertSame('value', $this->makeInstance()->get('key'), '');
    }

    /**
     * @test
     */
    public function updateTransient()
    {
        $this->makeInstance()->update('key', 'value');
        $this->assertSame('value', \get_transient('key'), '');
    }

    /**
     * @test
     */
    public function deleteTransient()
    {
        \set_transient('key', 'value');
        $this->assertSame('value', \get_transient('key'), '');
        $this->makeInstance()->delete('key');
        $this->assertFalse(\get_transient('key'), '');
        $this->assertFalse($this->makeInstance()->get('key'), '');
    }

    /**
     * @test
     */
    public function setMultipleTransient()
    {
        $this->makeInstance()->setMultiple(['key1' => 'value1', 'key2' => 'value2']);
        $this->assertSame('value1', \get_transient('key1'), '');
        $this->assertSame('value2', \get_transient('key2'), '');
    }

    /**
     * @test
     */
    public function getMultipleTransient()
    {
        \set_transient('key1', 'value1');
        \set_transient('key2', 'value2');
        $actual = $this->makeInstance()->getMultiple(['key1', 'key2']);
        $this->assertSame(['key1' => 'value1', 'key2' => 'value2'], \iterator_to_array($actual), '');
    }

    /**
     * @test
     */
    public function deleteMultipleTransient()
    {
        \set_transient('key1', 'value1');
        \set_transient('key2', 'value2');
        $this->assertSame('value1', \get_transient('key1'), '');
        $this->assertSame('value2', \get_transient('key2'), '');
        $this->makeInstance()->deleteMultiple(['key1', 'key2']);
        $this->assertFalse(\get_transient('key1'), '');
        $this->assertFalse(\get_transient('key2'), '');
    }
}
