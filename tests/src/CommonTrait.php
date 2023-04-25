<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

trait CommonTrait
{
    use CommonMultipleProviderTrait;

        /**
     * @test
     */
    public function getDefaultValueIfKeyDoesNotExists(): void
    {
        $this->assertSame('default', $this->makeInstance()->get('key', 'default'), '');
    }

    /**
     * @test
     */
    public function getZeroWhenValueIsZero(): void
    {
        $sut = $this->makeInstance();
        $sut->set('key', 0);
        $this->assertSame(0, $sut->get('key'), '');
    }

    /**
     * @test
     * @dataProvider iterableValueForSetMultipleProvider
     */
    public function setMultiple(iterable $values)
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setMultiple($values), '');
        $this->assertSame('value1', $sut->get('key1'), '');
        $this->assertSame('value2', $sut->get('key2'), '');
    }

    abstract protected function prepareSetMultipleReturnFalse(): void;

    /**
     * @test
     */
    public function setMultipleReturnFalse(): void
    {
        $this->prepareSetMultipleReturnFalse();
        $sut = $this->makeInstance();
        $this->assertFalse($sut->set('', 'value1'), '');
        $this->assertFalse($sut->setMultiple([
            '' => 'value1',
        ]), '');
    }

    /**
     * @test
     * @dataProvider iterableValuesForGetMultipleAndUpdateMultipleProvider
     */
    public function getMultiple(iterable $keys, iterable $expected = [])
    {
        $sut = $this->makeInstance();
        $sut->set('key1', 'value1');
        $sut->set('key2', 'value2');
        $actual =  $sut->getMultiple($keys);

        $count = 0;
        foreach ($actual as $key => $value) {
            $count++;
            $this->assertSame($expected[$key], $value, '');
        }
        $this->assertSame(2, $count, '');
    }

    /**
     * @test
     */
    public function getMultipleReturnDefaultValue(): void
    {
        $sut = $this->makeInstance();
        $sut->set('key2', 'value2');
        $actual =  $sut->getMultiple(['key1', 'key3'], 'default');

        $count = 0;
        foreach ($actual as $value) {
            $count++;
            $this->assertSame('default', $value, '');
        }
        $this->assertSame(2, $count, '');
    }

    /**
     * @test
     * @dataProvider iterableValuesForGetMultipleAndUpdateMultipleProvider
     */
    public function deleteMultiple(iterable $keys, iterable $expected = [])
    {
        $sut = $this->makeInstance();
        $sut->set('key1', 'value1');
        $sut->set('key2', 'value2');
        $this->assertSame('value1', $sut->get('key1'), '');
        $this->assertSame('value2', $sut->get('key2'), '');
        $this->assertTrue($this->makeInstance()->deleteMultiple($keys), '');
        $this->assertNull($sut->get('key1'), '');
        $this->assertNull($sut->get('key2'), '');
    }

    /**
     * @test
     */
    public function deleteMultipleReturnTrueWithNotExistentValue()
    {
        $this->assertNull($this->makeInstance()->get('key1'));
        $this->assertNull($this->makeInstance()->get('key3'));
        $this->assertTrue($this->makeInstance()->deleteMultiple(['key1', 'key3']), '');
    }

    /**
     * @test
     * @todo In the future make this test pass
     */
    public function deleteNotExistingValue()
    {
        $this->assertTrue($this->makeInstance()->delete('key1'), '');
    }

    /**
     * @test
     */
    public function deleteFromEmptyKeyShouldReturnFalse()
    {
        $this->assertFalse($this->makeInstance()->delete(''), '');
        $this->assertFalse($this->makeInstance()->deleteMultiple(['']), '');
    }
}
