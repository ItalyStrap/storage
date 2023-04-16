<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\Storage\Transient;

trait TransientTestsTrait
{
    use ValidateKeyLenghtTestTrait, NormalizeTtlTestTrait, CommonMultipleProviderTrait;

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
    public function getTransientDefaultValueIfKeyDoesNotExists(): void
    {
        $this->assertSame('default', $this->makeInstance()->get('key', 'default'), '');
    }

    /**
     * @test
     */
    public function testGetTransientWhenThaValueIsZero()
    {
        \set_transient('key', 0);
        $this->assertSame(0, $this->makeInstance()->get('key'), '');
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
        $this->assertNull($this->makeInstance()->get('key'), '');
    }

    /**
     * @test
     * @dataProvider iterableValueForSetMultipleProvider
     */
    public function setMultipleTransient(iterable $values)
    {
        $this->makeInstance()->setMultiple($values);
        $this->assertSame('value1', \get_transient('key1'), '');
        $this->assertSame('value2', \get_transient('key2'), '');
    }

    /**
     * @test
     * @dataProvider iterableValuesForGetMultipleAndUpdateMultipleProvider
     */
    public function getMultipleTransient(iterable $keys, iterable $expected = [])
    {
        \set_transient('key1', 'value1');
        \set_transient('key2', 'value2');
        $actual = $this->makeInstance()->getMultiple($keys);

        foreach ($actual as $key => $value) {
            $this->assertSame($expected[$key], $value, '');
        }
    }

    /**
     * @test
     * @dataProvider iterableValuesForGetMultipleAndUpdateMultipleProvider
     */
    public function deleteMultipleTransient(iterable $keys, iterable $expected = [])
    {
        \set_transient('key1', 'value1');
        \set_transient('key2', 'value2');
        $this->assertSame('value1', \get_transient('key1'), '');
        $this->assertSame('value2', \get_transient('key2'), '');
        $this->makeInstance()->deleteMultiple($keys);
        $this->assertFalse(\get_transient('key1'), '');
        $this->assertFalse(\get_transient('key2'), '');
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
