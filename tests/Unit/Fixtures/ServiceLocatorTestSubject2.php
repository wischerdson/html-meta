<?php

namespace Tests\Unit\Fixtures;

use Osmuhin\HtmlMeta\Container;
use Osmuhin\HtmlMeta\ServiceLocator;

class ServiceLocatorTestSubject2
{
	public Container $container;

	public function __construct()
	{
		ServiceLocator::register(
			$this->container = new Container()
		);
	}

	public function __destruct()
	{
		ServiceLocator::destructContainer();
	}

	public function getContainerViaServiceLocator(): Container
	{
		$closure = function () {
			return ServiceLocator::container();
		};

		return $closure();
	}
}
