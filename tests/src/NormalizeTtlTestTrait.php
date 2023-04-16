<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

trait NormalizeTtlTestTrait
{
    private function makeParserObject(): object
    {
        return new class {
            use \ItalyStrap\Storage\NormalizeTtlTrait {
                \ItalyStrap\Storage\NormalizeTtlTrait::parseTtl as parseTtlTrait;
            }

            public function parseTtl(?int $ttl): int
            {
                return $this->parseTtlTrait($ttl);
            }
        };
    }

    /**
     * @test
     */
    public function testParseTtlWithNull(): void
    {
        $this->assertSame(31536001, $this->makeParserObject()->parseTtl(null));
    }

    /**
     * @test
     */
    public function testParseTtlWithZero(): void
    {
        $this->assertSame(-1, $this->makeParserObject()->parseTtl(0));
    }

    /**
     * @test
     */
    public function testParseTtlWithOne(): void
    {
        $this->assertSame(1, $this->makeParserObject()->parseTtl(1));
    }

    /**
     * @test
     */
    public function testParseTtlWithOneYear(): void
    {
        $this->assertSame(31536001, $this->makeParserObject()->parseTtl(31536001));
    }
}
