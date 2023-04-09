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
    }

	// phpcs:ignore
	protected function _after() {
        \tad\FunctionMockerLe\undefineAll([
            'get_transient',
            'set_transient',
            'delete_transient',
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