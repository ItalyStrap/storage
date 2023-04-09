<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\WPUnit;

use ItalyStrap\Storage\Transient;
use ItalyStrap\Tests\TransientTestsTrait;
use ItalyStrap\Tests\WPTestCase;

class TransientTest extends WPTestCase
{
//    use TransientTestsTrait;

    private function makeInstance(): Transient
    {
        return new Transient();
    }
}
