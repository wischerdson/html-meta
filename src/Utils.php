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

		if (preg_match("/https?:\/\//i", $url)) {
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
		if (str_contains($url, '://')) {
			[$scheme, $url] = explode('://', $url, 2);
			$scheme .= '://';
		} else {
			$scheme = '';
		}

		if (str_contains($url, '/')) {
			[$domain, $path] = explode('/', $url, 2);
		} else {
			$domain = $url;
			$path = '/';
		}

		return [$scheme . $domain, trim($path, '/')];
	}
}
