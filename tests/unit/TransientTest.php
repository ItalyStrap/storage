<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Unit;

use ItalyStrap\Storage\Transient;
use ItalyStrap\Tests\TestCase;

class TransientTest extends TestCase
{
    // tests
    public function makeInstance(): Transient
    {
        return new Transient();
    }

    public function testInstanceOk(): void
    {
        $this->assertInstanceOf(Transient::class, $this->makeInstance());
    }

    /**
     * @test
     */
    public function setTransient(): void
    {
        $this->makeInstance()->set('key', 'value');
        $this->assertSame('value', \get_transient('key'), '');
    }
}
