<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

trait BinaryCacheDecoratorTestsTrait
{
    /**
     * @test
     */
    public function getAndSetBinaryData(): void
    {
        // Create a code for binary data assign to a variable
        // The code should be at least 3' chars long
        $binary_data = '';
        for ($i = 0; $i < 256; $i++) {
            $binary_data .= \chr(\rand(0, 255));
        }

        $sut = $this->makeInstance();
        $this->assertTrue($sut->set('foo', $binary_data));
        $this->assertSame($binary_data, $sut->get('foo'));
    }
    /**
     * @test
     */
    public function getAndUpdateBinaryData(): void
    {
        // Create a code for binary data assign to a variable
        // The code should be at least 3' chars long
        $binary_data = '';
        for ($i = 0; $i < 256; $i++) {
            $binary_data .= \chr(\rand(0, 255));
        }

        $sut = $this->makeInstance();
        $this->assertTrue($sut->update('foo', $binary_data));
        $this->assertSame($binary_data, $sut->get('foo'));
    }

    /**
     * @test
     */
    public function generateKeyShouldNotChange(): void
    {
        $key = 'test_key';
        $value = 'test_value';
        $encodedValue = base64_encode($value);
        $sut = $this->makeInstance();

        $reflection = new \ReflectionClass($sut);
        $method = $reflection->getMethod('generateKey');
        $method->setAccessible(true);
        $generatedKey = $method->invokeArgs($sut, [$key]);

        $this->assertSame($generatedKey, 'fc5497f48a82e07ccc03c3cbe9830614');
    }
}
