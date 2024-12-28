<?php

namespace Osmuhin\HtmlMeta;

use Symfony\Component\Mime\MimeTypes;
use Throwable;

class Utils
{
	public static function assignPropertyIgnoringErrors(string $property): callable
	{
		return function ($value, $object) use ($property) {
			try {
				$object->{$property} = $value;
			} catch (Throwable $th) {

			}
		};
	}

	public static function assignPropertyForceOverwrite(string $property): callable
	{
		return function ($value, $object) use ($property) {
			$object->{$property} = $value;
		};
	}

	public static function assignPropertyAndGuessMimeType(string $property, string $propertyType = 'type'): callable
	{
		return function ($value, $object) use ($property, $propertyType) {
			$object->{$property} = $value;

			if ($maybeExtension = Utils::guessExtension($value)) {
				$object->{$propertyType} ??= Utils::guessMimeType($maybeExtension);
			}
		};
	}

	public static function assignAccordingToTheMap(array $map, object $object, string $name, string $content): bool
	{
		if (isset($map[$name])) {
			if (is_callable($map[$name])) {
				call_user_func($map[$name], $content, $object);

				return true;
			}

			$object->{$map[$name]} ??= $content;

			return true;
		}

		return false;
	}

	public static function guessMimeType(string $extension): ?string
	{
		$types = MimeTypes::getDefault()->getMimeTypes($extension);

		return $types ? $types[0] : null;
	}

	public static function guessExtension(string $path): ?string
	{
		$explodedPath = explode('/', $path);
		$file = array_pop($explodedPath);

		$explodedName = explode('.', $file);

		return \count($explodedName) > 1 ? array_pop($explodedName) : null;
	}
}
