<?php

namespace Osmuhin\HtmlMeta;

use InvalidArgumentException;

class Container
{
	private array $bindings = [];

	private array $instances = [];

	public function bind(string $key, callable|object $value): void
	{
		$this->bindings[$key] = $value;
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	public function get(string $key)
	{
		if (isset($this->instances[$key])) {
			return $this->instances[$key];
		}

		if (!isset($this->bindings[$key])) {
			throw new InvalidArgumentException("No binding found for key \"{$key}\"");
		}

		$binding = $this->bindings[$key];

		return $this->instances[$key] = is_callable($binding) ? $binding($this) : $binding;
	}

	public function has(string $key): bool
	{
		return isset($this->instances[$key]);
	}
}
