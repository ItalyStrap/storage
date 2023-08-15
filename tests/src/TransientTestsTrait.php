<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\Storage\Transient;
use ItalyStrap\StorageTests\CommonStoreMultipleTestsTrait;

trait TransientTestsTrait
{
    use ValidateKeyLenghtTestTrait, NormalizeTtlTestTrait, CommonStoreMultipleTestsTrait;

    /**
     * @test
     */
    public function instanceOk(): void
    {
        $this->assertInstanceOf(Transient::class, $this->makeInstance());
    }

    /**
     * @test
     */
    public function setTransient()
    {
        $this->assertTrue($this->makeInstance()->set('key', 'value'));
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
    public function getTransientWhenValueIsZero()
    {
        \set_transient('key', 0);
        $this->assertSame(0, $this->makeInstance()->get('key'), '');
    }

    /**
     * @test
     */
    public function updateTransient()
    {
        $this->assertTrue($this->makeInstance()->update('key', 'value'));
        $this->assertSame('value', \get_transient('key'), '');
    }

    /**
     * @test
     */
    public function deleteTransient()
    {
        \set_transient('key', 'value');
        $this->assertSame('value', \get_transient('key'), '');
        $sut = $this->makeInstance();

        $this->assertTrue($sut->delete('key'), '');
        $this->assertFalse(\get_transient('key'), '');
        $this->assertNull($sut->get('key'), '');
    }

    public static function expiredTtlProvider(): iterable
    {
        yield 'Less 1' => [
            -1,
        ];

        yield 'Zero' => [
            0,
        ];
    }

    /**
     * @test
     * @dataProvider expiredTtlProvider
     */
    public function expiredTtl($ttl): void
    {
        $this->makeInstance()->set('key', 'value', $ttl);
        $this->assertFalse(\get_transient('key'), '');
    }

    /**
     * @test
     */
    public function ttlValueAsNull(): void
    {
        $this->makeInstance()->set('key', 'value', null);
        $this->assertSame('value', \get_transient('key'), '');
    }
}
