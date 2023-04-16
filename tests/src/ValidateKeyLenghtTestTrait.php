<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

trait ValidateKeyLenghtTestTrait
{
    public static function lengthProvider(): iterable
    {
        yield '173' => [173];
        yield '174' => [174];
    }

    /**
     * @test
     * @dataProvider lengthProvider
     */
    public function testInvalidKeyLengthOnSet(int $length): void
    {
        $this->commonExpectedException();
        $this->makeInstance()->set(\str_repeat('a', $length), 'value');
    }

    public function testValidKeyLengthOnSet(): void
    {
        $this->makeInstance()->set(\str_repeat('a', 172), 'value');
    }

    /**
     * @test
     * @dataProvider lengthProvider
     */
    public function testInvalidKeyLengthOnGet(int $length): void
    {
        $this->commonExpectedException();
        $this->makeInstance()->get(\str_repeat('a', $length), 'value');
    }

    public function testValidKeyLengthOnGet(): void
    {
        $this->makeInstance()->get(\str_repeat('a', 172), 'value');
    }

    /**
     * @test
     * @dataProvider lengthProvider
     */
    public function testInvalidKeyLengthOnUpdate(int $length): void {
        $this->commonExpectedException();
        $this->makeInstance()->update(\str_repeat('a', $length), 'value');
    }

    public function testValidKeyLengthOnUpdate(): void {
        $this->makeInstance()->update(\str_repeat('a', 172), 'value');
    }

    /**
     * @test
     * @dataProvider lengthProvider
     */
    public function testInvalidKeyLengthOnDelete(int $length): void
    {
        $this->commonExpectedException();
        $this->makeInstance()->delete(\str_repeat('a', $length));
    }

    public function testValidKeyLengthOnDelete(): void
    {
        $this->makeInstance()->delete(\str_repeat('a', 172));
    }

    /**
     * @return void
     */
    private function commonExpectedException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The maximum length key "');
        $this->expectExceptionMessage('" is 172 characters');
    }
}