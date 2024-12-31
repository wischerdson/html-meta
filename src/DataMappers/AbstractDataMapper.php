<?php

namespace Osmuhin\HtmlMeta\DataMappers;

use Osmuhin\HtmlMeta\Config;
use Osmuhin\HtmlMeta\Contracts\DataMapper;
use Osmuhin\HtmlMeta\Dto\Meta;
use Osmuhin\HtmlMeta\ServiceLocator;
use Osmuhin\HtmlMeta\Utils;
use Throwable;

abstract class AbstractDataMapper implements DataMapper
{
	protected Meta $meta;

	protected Config $config;

	public function __construct()
	{
		$container = ServiceLocator::container();

		$this->meta = $container->get(Meta::class);
		$this->config = $container->get(Config::class);
	}

	protected static function assignAccordingToTheMap(array $map, object $object, string $name, string $content): bool
	{
		if (isset($map[$name])) {
			self::assignPropertyWithObject($object, $map[$name], $content);

			return true;
		}

		return false;
	}

	protected static function assignPropertyWithObject(object $object, string|callable $property, string $value)
	{
		if (is_callable($value)) {
			$value($value, $object);
		} else {
			$object->{$property} ??= $value;
		}
	}

	protected static function ignoreErrors(string $property): callable
	{
		return function ($value, $object) use ($property) {
			try {
				self::assignPropertyWithObject($object, $property, $value);
			} catch (Throwable $th) {

			}
		};
	}

	protected static function int(string $property): callable
	{
		return function (string $value, object $object) use ($property) {
			if ($this->config->shouldUseTypeConversion()) {
				$value = ctype_digit($value) ? (int) $value : null;
			}

			self::assignPropertyWithObject($object, $property, $value);
		};
	}

	protected static function url(string $property): callable
	{
		return function (string $value, object $object) use ($property) {
			if ($this->config->shouldProcessUrls()) {
				$value = Utils::processUrl($value);
			}

			self::assignPropertyWithObject($object, $property, $value);
		};
	}

	protected static function forceOverwrite(string $property): callable
	{
		return function ($value, $object) use ($property) {
			$object->{$property} = $value;
		};
	}

	protected static function guessMimeType(string $property, string $propertyType = 'type'): callable
	{
		return function ($value, $object) use ($property, $propertyType) {
			self::assignPropertyWithObject($object, $property, $value);

			if ($maybeExtension = Utils::guessExtension($value)) {
				self::assignPropertyWithObject($object, $propertyType, Utils::guessMimeType($maybeExtension));
			}
		};
	}
}
