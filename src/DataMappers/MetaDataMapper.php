<?php

namespace Osmuhin\HtmlMeta\DataMappers;

class MetaDataMapper extends AbstractDataMapper
{
	protected static function getMap(): array
	{
		return [
			'apple-itunes-app' => 'appleItunesApp',
			'apple-mobile-web-app-capable' => 'appleMobileWebAppCapable',
			'apple-mobile-web-app-status-bar-style' => 'appleMobileWebAppStatusBarStyle',
			'application-name' => 'applicationName',
			'author' => 'author',
			'color-scheme' => 'colorScheme',
			'copyright' => 'copyright',
			'description' => 'description',
			'format-detection' => 'formatDetection',
			'generator' => 'generator',
			'keywords' => 'keywords',
			'referrer' => 'referrer',
			'robots' => 'robots',
			'viewport' => 'viewport',
		];
	}

	public function assign(string $key, string $content): bool
	{
		return self::assignAccordingToTheMap(
			self::getMap(),
			$this->meta->httpEquiv,
			$key,
			$content
		);
	}
}