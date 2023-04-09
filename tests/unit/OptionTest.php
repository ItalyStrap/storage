<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit;

use ItalyStrap\Storage\Option;
use ItalyStrap\Tests\OptionTestsTrait;
use ItalyStrap\Tests\TestCase;

class OptionTest extends TestCase
{
    use OptionTestsTrait;

    public function makeInstance(): Option
    {
        return new Option();
    }
}
