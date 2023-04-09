<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\WPUnit;

use ItalyStrap\Storage\BinaryCacheDecorator;
use ItalyStrap\Storage\Transient;
use ItalyStrap\Tests\BinaryCacheDecoratorTestsTrait;
use ItalyStrap\Tests\TransientTestsTrait;
use ItalyStrap\Tests\WPTestCase;

class BinaryCacheDecoratorTest extends WPTestCase
{
    use TransientTestsTrait, BinaryCacheDecoratorTestsTrait;
    public function makeInstance(): BinaryCacheDecorator
    {
        return new BinaryCacheDecorator(new Transient());
    }

    /**
     * @test
     */
    public function testInstanceOk(): void
    {
        $sut = $this->makeInstance();
        $this->assertInstanceOf(BinaryCacheDecorator::class, $sut);
    }
}
