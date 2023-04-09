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
}
