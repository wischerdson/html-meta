<?php

namespace Osmuhin\HtmlMeta;

use RuntimeException;

class ServiceLocator
{
	private static array $containers = [];

	/**
	 * @throws \RuntimeException
	 */
	public static function container(): Container
	{
		$trace = debug_backtrace();

		while ($item = next($trace)) {
			if (!$object = @$item['object']) {
				continue;
			}

			$class = $object::class;

			if (isset(self::$containers[$class])) {
				$key = spl_object_id($object);

				if (isset(self::$containers[$class][$key])) {
					return self::$containers[$class][$key];
				}

				throw new RuntimeException("No container associated with object {$key} of class {$class}");
			}
		}

		throw new RuntimeException('Unable to find container');
	}

	public static function register(Container $container): void
	{
		$trace = debug_backtrace();
		$object = next($trace)['object'];
		$key = spl_object_id($object);
		$class = $object::class;

		if (!isset(self::$containers[$class])) {
			self::$containers[$class] = [];
		}

		self::$containers[$class][$key] = $container;
	}
}
