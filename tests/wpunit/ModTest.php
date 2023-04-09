<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\WPUnit;

use ItalyStrap\Storage\Mod;
use ItalyStrap\Tests\ModTestTrait;
use ItalyStrap\Tests\WPTestCase;

class ModTest extends WPTestCase
{
    use ModTestTrait;

    public function makeInstance(): Mod
    {
        return new Mod();
    }
}
