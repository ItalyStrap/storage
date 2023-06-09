<?php
declare(strict_types=1);

namespace ItalyStrap\Storage;

trait ValidateKeyLength
{
    private function assertKeyLength(string $key): void
    {
        if (\strlen($key) > 172) {
            throw new \InvalidArgumentException(\sprintf(
                'The maximum length key "%s" is %d characters',
                $key,
                172
            ));
        }
    }
}
