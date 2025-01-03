<?php

namespace Tests\Traits;

use Osmuhin\HtmlMeta\Config;
use Osmuhin\HtmlMeta\Container;
use Osmuhin\HtmlMeta\Dto\Meta;
use Osmuhin\HtmlMeta\ServiceLocator;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;

trait SetupContainer
{
	protected Meta $meta;

	protected Config $config;

	#[Before]
	protected function setUpContainer(): void
	{
		$this->meta = new Meta();
		$this->config = new Config();

		ServiceLocator::register(
			$this->makeContainer()
		);

		parent::setUp();
	}

	#[After]
	protected function tearDownContainer(): void
	{
		ServiceLocator::destructContainer();

		parent::setUp();
	}

	private function makeContainer(): Container
	{
		$container = new Container();
		$container->bind(Meta::class, $this->meta);
		$container->bind(Config::class, $this->config);

		return $container;
	}
}
