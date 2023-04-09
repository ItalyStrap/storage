<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

trait OptionTestsTrait
{

    /**
     * @test
     */
    public function getOption(): void
    {
        $sut = $this->makeInstance();
        \add_option('test', 'test');
        $this->assertSame('test', $sut->get('test'));
        $this->assertSame('test', \get_option('test'));
    }

    /**
     * @test
     */
    public function setOption(): void
    {
        $sut = $this->makeInstance();
        $sut->set('test', 'test');
        $this->assertSame('test', $sut->get('test'));
        $this->assertSame('test', \get_option('test'));
    }

    /**
     * @test
     */
    public function updateOption(): void
    {
        $sut = $this->makeInstance();
        $sut->set('test', 'test');
        $this->assertSame('test', $sut->get('test'));
        $this->assertSame('test', \get_option('test'));
        $sut->update('test', 'test2');
        $this->assertSame('test2', $sut->get('test'));
        $this->assertSame('test2', \get_option('test'));
    }

    /**
     * @test
     */
    public function deleteOption(): void
    {
        $sut = $this->makeInstance();
        $sut->set('test', 'test');
        $this->assertSame('test', $sut->get('test'));
        $this->assertSame('test', \get_option('test'));
        $sut->delete('test');
        $this->assertNull($sut->get('test'));
        $this->assertFalse(\get_option('test'));
    }

    /**
     * @test
     */
    public function setMultipleOption(): void
    {
        $sut = $this->makeInstance();
        $sut->setMultiple([
            'test' => 'test',
            'test2' => 'test2',
        ]);
        $this->assertSame('test', $sut->get('test'));
        $this->assertSame('test', \get_option('test'));
        $this->assertSame('test2', $sut->get('test2'));
        $this->assertSame('test2', \get_option('test2'));
    }

    /**
     * @test
     */
    public function getMultipleOption(): void
    {
        $sut = $this->makeInstance();
        $sut->setMultiple([
            'test' => 'test',
            'test2' => 'test2',
        ]);
        $actual = $sut->getMultiple(['test', 'test2']);

        $this->assertSame([
            'test' => 'test',
            'test2' => 'test2',
        ], \iterator_to_array($actual));

        $this->assertSame('test', \get_option('test'));
        $this->assertSame('test2', \get_option('test2'));
    }

    /**
     * @test
     */
    public function deleteMultipleOption(): void
    {
        $sut = $this->makeInstance();
        $sut->setMultiple([
            'test' => 'test',
            'test2' => 'test2',
        ]);
        $this->assertSame('test', $sut->get('test'));
        $this->assertSame('test', \get_option('test'));
        $this->assertSame('test2', $sut->get('test2'));
        $this->assertSame('test2', \get_option('test2'));
        $sut->deleteMultiple(['test', 'test2']);
        $this->assertNull($sut->get('test'));
        $this->assertFalse(\get_option('test'));
        $this->assertNull($sut->get('test2'));
        $this->assertFalse(\get_option('test2'));
    }
}
