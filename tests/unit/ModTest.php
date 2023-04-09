<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit;

use ItalyStrap\Storage\Mod;
use ItalyStrap\Tests\ModTestTrait;
use ItalyStrap\Tests\TestCase;

class ModTest extends TestCase
{
    use ModTestTrait;

    public function makeInstance(): Mod
    {
        return new Mod();
    }
}
