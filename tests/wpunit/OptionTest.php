<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\WPUnit;

use ItalyStrap\Storage\Option;
use ItalyStrap\Tests\OptionTestsTrait;
use ItalyStrap\Tests\WPTestCase;

class OptionTest extends WPTestCase
{
    use OptionTestsTrait;

    private function makeInstance(): Option
    {
        return new Option();
    }
}
