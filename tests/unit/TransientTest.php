<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Unit;

use ItalyStrap\Storage\Transient;
use ItalyStrap\Tests\TestCase;
use ItalyStrap\Tests\TransientTestsTrait;

class TransientTest extends TestCase
{
    use TransientTestsTrait;

    public function makeInstance(): Transient
    {
        return new Transient();
    }
}
