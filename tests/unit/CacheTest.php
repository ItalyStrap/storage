<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit;

use ItalyStrap\Storage\Cache;
use ItalyStrap\Tests\CacheTestTrait;
use ItalyStrap\Tests\TestCase;

class CacheTest extends TestCase
{
    use CacheTestTrait;

    private function makeInstance(): Cache
    {
        return new Cache();
    }
}
