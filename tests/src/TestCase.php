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
    protected bool $set_transient_return = true;
    protected bool $delete_transient_return = true;
    protected ?int $ttl = 0;

	// phpcs:ignore
	protected function _before() {
        $this->defineFunction('get_transient', function (string $key) {
            return $this->store[ $key ] ?? false;
        });

        $this->defineFunction('set_transient', function (string $key, $value, $ttl = 0) {
            $this->ttl = $ttl;
            $this->store[ $key ] = $value;
            return $this->set_transient_return;
        });

        $this->defineFunction('delete_transient', function (string $key) {
            unset($this->store[ $key ]);
            return $this->delete_transient_return;
        });

        $this->defineFunction('add_option', function ($key, $value, $deprecated = '', $autoload = 'yes') {
            $this->store[ $key ] = $value;
            return $this->set_transient_return;
        });

        $this->defineFunction('update_option', function ($key, $value, $deprecated = '') {
            $this->store[ $key ] = $value;
            return $this->set_transient_return;
        });

        $this->defineFunction('delete_option', function ($key) {
            unset($this->store[ $key ]);
            return $this->delete_transient_return;
        });

        $this->defineFunction('get_option', function ($key, $default = false) {
            return $this->store[ $key ] ?? $default;
        });

        $this->defineFunction('wp_cache_get', function ($key, $group = '', $force = false, &$found = null) {
            $found = isset($this->store[ $key ]);
            return $this->store[ $key ] ?? false;
        });

        $this->defineFunction('wp_cache_set', function ($key, $data, $group = '', $expire = 0) {
            $this->store[ $key ] = $data;
            return $this->set_transient_return;
        });

        $this->defineFunction('wp_cache_delete', function ($key, $group = '') {
            unset($this->store[ $key ]);
            return $this->delete_transient_return;
        });

        $this->defineFunction('wp_cache_add', function ($key, $data, $group = '', $expire = 0) {
            $this->store[ $key ] = $data;
            return $this->set_transient_return;
        });

        $this->defineFunction('wp_cache_replace', function ($key, $data, $group = '', $expire = 0) {
            $this->store[ $key ] = $data;
            return $this->set_transient_return;
        });

        $this->defineFunction('wp_cache_incr', function ($key, $offset = 1, $group = '') {
            $this->store[ $key ] += $offset;
            return $this->store[ $key ];
        });

        $this->defineFunction('wp_cache_decr', function ($key, $offset = 1, $group = '') {
            $this->store[ $key ] -= $offset;
            return $this->store[ $key ];
        });

        $this->defineFunction('wp_cache_flush', function () {
            $this->store = [];
            return true;
        });

        $this->defineFunction('wp_cache_set_multiple', function ($keys, $group = '', $expire = 0) {
            foreach ($keys as $key => $value) {
                $this->store[ $key ] = $value;
            }
            return $this->set_transient_return;
        });

        $this->defineFunction('wp_cache_get_multiple', function ($keys, $group = '', $force = false) {
            $result = [];
            foreach ($keys as $key) {
                $result[ $key ] = $this->store[ $key ] ?? false;
            }
            return $result;
        });

        $this->defineFunction('wp_cache_delete_multiple', function ($keys, $group = '') {
            foreach ($keys as $key) {
                unset($this->store[ $key ]);
            }
            return $this->delete_transient_return;
        });

        $this->defineFunction('get_theme_mod', function ($key, $default = false) {
            return $this->store[ $key ] ?? $default;
        });

        $this->defineFunction('set_theme_mod', function ($key, $value) {
            $this->store[ $key ] = $value;
            return $this->set_transient_return;
        });

        $this->defineFunction('remove_theme_mod', function ($key) {
            unset($this->store[ $key ]);
            return $this->delete_transient_return;
        });

        $this->defineFunction('remove_theme_mods', function () {
            $this->store = [];
            return $this->delete_transient_return;
        });
    }

	// phpcs:ignore
	protected function _after() {
        \tad\FunctionMockerLe\undefineAll([
            'get_transient',
            'set_transient',
            'delete_transient',
            'add_option',
            'update_option',
            'delete_option',
            'get_option',
            'wp_cache_get',
            'wp_cache_set',
            'wp_cache_delete',
            'wp_cache_add',
            'wp_cache_replace',
            'wp_cache_incr',
            'wp_cache_decr',
            'wp_cache_flush',
            'wp_cache_set_multiple',
            'wp_cache_get_multiple',
            'wp_cache_delete_multiple',
            'get_theme_mod',
            'set_theme_mod',
            'remove_theme_mod',
            'remove_theme_mods',
        ]);
        $this->store = [];
        $this->set_transient_return = true;
        $this->delete_transient_return = true;
//        $this->prophet->checkPredictions();
    }

    protected function defineFunction(string $func_name, callable $callable): void
    {
		// phpcs:ignore
		\tad\FunctionMockerLe\define($func_name, $callable);
    }
}
