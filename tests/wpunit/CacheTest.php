<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\WPUnit;

use ItalyStrap\Storage\Cache;
use ItalyStrap\Tests\CacheTestTrait;
use ItalyStrap\Tests\WPTestCase;

class CacheTest extends WPTestCase
{
    use CacheTestTrait;

    private function makeInstance(): Cache
    {
        return new Cache();
    }
}
