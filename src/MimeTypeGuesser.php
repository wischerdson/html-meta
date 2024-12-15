<?php

namespace Osmuhin\HtmlMeta;

use Symfony\Component\Mime\MimeTypes;

class MimeTypeGuesser
{
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
