<?php

namespace Osmuhin\HtmlMeta;

use RuntimeException;

class ServiceLocator
{
	private static array $containers = [];

	private static int $count = 0;

	public static function container(): Container
	{
		if (self::$count === 1) {
			return current(current(self::$containers));
		}

		[$class, $objectId] = self::getContainerCredentials(
			debug_backtrace()
		);

		return self::$containers[$class][$objectId];
	}

	public static function register(Container $container): void
	{
		$trace = debug_backtrace();
		$object = next($trace)['object'];
		$objectId = spl_object_id($object);
		$class = $object::class;

		if (!isset(self::$containers[$class])) {
			self::$containers[$class] = [];
		}

		self::$containers[$class][$objectId] = $container;
		self::$count++;
	}

	public static function destructContainer(): void
	{
		[$class, $objectId] = self::getContainerCredentials(
			debug_backtrace()
		);

		unset(self::$containers[$class][$objectId]);
	}

	/**
	 * @throws \RuntimeException
	 */
	private static function getContainerCredentials(array $trace): array
	{
		while ($item = next($trace)) {
			if (!$object = @$item['object']) {
				continue;
			}

			$class = $object::class;

			if (isset(self::$containers[$class])) {
				$objectId = spl_object_id($object);

				if (isset(self::$containers[$class][$objectId])) {
					return [$class, $objectId];
				}

				throw new RuntimeException("No container associated with object {$objectId} of class {$class}");
			}
		}

		throw new RuntimeException('Unable to find container');
	}
}
