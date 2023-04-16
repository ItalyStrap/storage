<?php
declare(strict_types=1);

namespace ItalyStrap\Storage;

/**
 * @psalm-api
 */
class Transient implements CacheInterface
{

    use ValidateKeyLength, NormalizeTtlTrait, MultipleTrait, SetMultipleCacheTrait;

    public function set(string $key, $value, ?int $ttl = null): bool
    {
        $this->assertKeyLength($key);
        $ttl = $this->parseTtl($ttl);
        return \set_transient($key, $value, $ttl);
    }

    public function get(string $key, $default = null)
    {
        $this->assertKeyLength($key);
        $value = \get_transient($key);
        if ($value === 0) {
            return $value;
        }

        return $value ?: $default;
    }

    public function update(string $key, $value, ?int $ttl = null): bool
    {
        return $this->set($key, $value, $ttl);
    }

    public function delete(string $key): bool
    {
        $this->assertKeyLength($key);
        return \delete_transient($key);
    }
}
