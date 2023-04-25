<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use Prophecy\PhpUnit\ProphecyTrait;

class TestCase extends Unit
{
    use ProphecyTrait;

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected array $store = [];
    protected bool $set_return_value = true;
    protected bool $delete_return_value = true;
    protected ?int $ttl = 0;

    /**
     * @var \Closure[]
     */
    private array $mockFunctionDefinitions;

    // phpcs:ignore
    protected function _before() {
        $this->mockFunctionDefinitions = [
            'get_transient' => function (string $key) {
                if ($this->ttl && $this->ttl < 0) {
                    return false;
                }

                return $this->store[ $key ] ?? false;
            },
            'set_transient' => function (string $key, $value, $ttl = 0): bool {
                $this->ttl = $ttl;
                $this->store[ $key ] = $value;
                return $this->set_return_value;
            },
            'delete_transient' => function (string $key): bool {
                if (!\array_key_exists($key, $this->store)) {
                    return false;
                }

                unset($this->store[ $key ]);
                return $this->delete_return_value;
            },
            'add_option' => function ($key, $value, $deprecated = '', $autoload = 'yes'): bool {
                $this->store[ $key ] = $value;
                return $this->set_return_value;
            },
            'update_option' => function ($key, $value, $deprecated = ''): bool {
                $this->store[ $key ] = $value;
                return $this->set_return_value;
            },
            'delete_option' => function ($key): bool {
                if (!\array_key_exists($key, $this->store)) {
                    return false;
                }

                unset($this->store[ $key ]);
                return $this->delete_return_value;
            },
            'get_option' => function ($key, $default = false) {
                return $this->store[ $key ] ?? $default;
            },
            'wp_cache_get' => function ($key, $group = '', $force = false, &$found = null) {
                if ($this->ttl && $this->ttl < 0) {
                    return false;
                }

                $found = isset($this->store[ $key ]);
                return $this->store[ $key ] ?? false;
            },
            'wp_cache_set' => function ($key, $data, $group = '', $expire = 0): bool {
                $this->store[ $key ] = $data;
                return $this->set_return_value;
            },
            'wp_cache_delete' => function ($key, $group = ''): bool {
                unset($this->store[ $key ]);
                return $this->delete_return_value;
            },
            'wp_cache_add' => function ($key, $data, $group = '', $expire = 0): bool {
                if ($this->ttl && $this->ttl < 0) {
                    return false;
                }

                $this->store[ $key ] = $data;
                return $this->set_return_value;
            },
            'wp_cache_replace' => function ($key, $data, $group = '', $expire = 0): bool {
                if ($this->ttl && $this->ttl < 0) {
                    return false;
                }

                $this->store[ $key ] = $data;
                return $this->set_return_value;
            },
            'wp_cache_incr' => function ($key, $offset = 1, $group = ''): int {
                $this->store[ $key ] += $offset;
                return $this->store[ $key ];
            },
            'wp_cache_decr' => function ($key, $offset = 1, $group = ''): int {
                $this->store[ $key ] -= $offset;
                return $this->store[ $key ];
            },
            'wp_cache_flush' => function (): bool {
                $this->store = [];
                return true;
            },
            'wp_cache_set_multiple' => function ($keys, $group = '', $expire = 0): array {
                if ($this->ttl && $this->ttl < 0) {
                    return [false];
                }

                foreach ($keys as $key => $value) {
                    $this->store[ $key ] = $value;
                }
                return [$this->set_return_value];
            },
            'wp_cache_get_multiple' => function ($keys, $group = '', $force = false): array {
                $result = [];
                foreach ($keys as $key) {
                    $result[ $key ] = $this->store[ $key ] ?? false;
                }
                return $result;
            },
            'wp_cache_delete_multiple' => function ($keys, $group = ''): array {
                $result = [];
                foreach ($keys as $key) {
                    if (!\array_key_exists($key, $this->store)) {
                        $result[ $key ] = false;
                    }
                    unset($this->store[ $key ]);
                }
                return $result;
            },

            'get_theme_mod' => function ($key, $default = false) {
                return $this->store[ $key ] ?? $default;
            },

            'set_theme_mod' => function ($key, $value): bool {
                $this->store[ $key ] = $value;
                return $this->set_return_value;
            },

            'remove_theme_mod' => function ($key): bool {
                if (!\array_key_exists($key, $this->store)) {
                    return false;
                }

                unset($this->store[ $key ]);
                return $this->delete_return_value;
            },

            'remove_theme_mods' => function (): bool {
                $this->store = [];
                return true;
            },
        ];

        \tad\FunctionMockerLe\defineWithMap($this->mockFunctionDefinitions);
    }

	// phpcs:ignore
	protected function _after() {
        \tad\FunctionMockerLe\undefineAll(\array_keys($this->mockFunctionDefinitions));
        $this->store = [];
        $this->set_return_value = true;
        $this->delete_return_value = true;
        $this->ttl = 0;
//        $this->prophet->checkPredictions();
    }

    protected function defineFunction(string $func_name, callable $callable): void
    {
		// phpcs:ignore
		\tad\FunctionMockerLe\define($func_name, $callable);
    }

    protected function prepareSetMultipleReturnFalse(): void
    {
        $this->set_return_value = false;
        $this->delete_return_value = false;
    }
}
