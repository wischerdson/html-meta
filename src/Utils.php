<?php

namespace Osmuhin\HtmlMeta;

use Symfony\Component\Mime\MimeTypes;

class Utils
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

	public static function processUrl(string $url): string
	{
		/** @var \Osmuhin\HtmlMeta\Config $config */
		$config = ServiceLocator::container()->get(Config::class);

		/**
		 * true if the domain part is found and the string has at least one slash "/"
		 */
		if (str_contains($url, '/') && self::splitUrl($url)[0] !== '') {
			return $url;
		}

		[$domain, $path] = $config->getBaseUrl();

		if (str_starts_with($url, '/')) {
			return $domain . $url;
		}

		return implode('/', [$domain, $path, $url]);
	}

	public static function splitUrl(string $url): array
	{
		$path = preg_replace("/^(https?:\/\/.*?\/)|^(\/\/.*?\/)/i", '', $url);
		$domain = mb_substr($url, 0, mb_strlen($url) - mb_strlen($path));

		return [rtrim($domain, '/'), trim($path, '/')];
	}
}
