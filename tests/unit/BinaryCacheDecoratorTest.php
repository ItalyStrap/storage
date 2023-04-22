<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit;

use ItalyStrap\Storage\BinaryCacheDecorator;
use ItalyStrap\Storage\Transient;
use ItalyStrap\Tests\TestCase;
use ItalyStrap\Tests\TransientTestsTrait;
use ItalyStrap\Tests\BinaryCacheDecoratorTestsTrait;

class BinaryCacheDecoratorTest extends TestCase
{
    use TransientTestsTrait, BinaryCacheDecoratorTestsTrait;

    public function makeInstance(): BinaryCacheDecorator
    {
        return new BinaryCacheDecorator(new Transient());
    }

    /**
     * @test
     */
    public function instanceOk(): void
    {
        $sut = $this->makeInstance();
        $this->assertInstanceOf(BinaryCacheDecorator::class, $sut);
    }
}
