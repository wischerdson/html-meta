<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;

class HttpEquivDistributor extends AbstractDistributor
{
	private string $name;

	public function canHandle(Element $el): bool
	{
		if (!$name = @$el->attributes['http-equiv']) {
			return false;
		}

		if (!$name = mb_strtolower(trim($name), 'UTF-8')) {
			return false;
		}

		return (bool) $this->name = $name;
	}

	public function handle(Element $el): void
	{
		if (!$content = @$el->attributes['content']) {
			return;
		}

		self::assignAccordingToTheMap(
			self::getPropertiesMap(),
			$this->meta->httpEquiv,
			$this->name,
			$content
		) || $this->meta->httpEquiv->other[$this->name] = $content;
	}

	protected static function getPropertiesMap(): array
	{
		return [
			'viewport' => 'viewport',
			'description' => 'description',
			'color-scheme' => 'colorScheme',
			'author' => 'author',
			'keywords' => 'keywords',
			'application-name' => 'applicationName',
			'generator' => 'generator',
			'referrer' => 'referrer',
			'copyright' => 'copyright',
			'robots' => 'robots',
			'apple-mobile-web-app-capable' => 'appleMobileWebAppCapable',
			'apple-mobile-web-app-status-bar-style' => 'appleMobileWebAppStatusBarStyle',
			'format-detection' => 'formatDetection',
			'apple-itunes-app' => 'appleItunesApp',
		];
	}
}
