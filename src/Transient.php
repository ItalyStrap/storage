<?php
declare(strict_types=1);

namespace ItalyStrap\Storage;

/**
 * @psalm-api
 */
class Transient implements CacheInterface
{

    use ValidateKeyLength, NormalizeTtlTrait, MultipleTrait, SetMultipleCacheTrait;

    public function get(string $key, $default = null)
    {
        $this->assertKeyLength($key);
        return \get_transient($key) ?? $default;
    }

    public function set(string $key, $value, ?int $ttl = null): bool
    {
        $this->assertKeyLength($key);

        $ttl = $this->parseTtl($ttl);

        return (bool)\set_transient($key, $value, $ttl);
    }

    public function update(string $key, $value, ?int $ttl = null): bool
    {
        $this->assertKeyLength($key);
        return $this->set($key, $value, $ttl);
    }

    public function delete(string $key): bool
    {
        $this->assertKeyLength($key);
        return (bool)\delete_transient($key);
    }
}
