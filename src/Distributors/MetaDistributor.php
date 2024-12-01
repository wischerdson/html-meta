<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;

class MetaDistributor extends AbstractDistributor
{
	public function canHandle(Element $el): bool
	{
		return $el->name === 'meta';
	}

	public function handle(Element $el): void
	{
		if ($this->meta->charset = @$el->attributes['charset']) {
			return;
		}

		if ($name = @$el->attributes['name']) {
			$this->handleNamedMeta($name, $el);
		}
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

	protected function handleNamedMeta(string $name, Element $meta): void
	{
		if (!$name = mb_strtolower(trim($name), 'UTF-8')) {
			return;
		}

		if (!$content = @$meta->attributes['content']) {
			return;
		}

		self::assignAccordingToTheMap(
			self::getPropertiesMap(),
			$this->meta,
			$name,
			$content
		);

		switch ($name) {
			case 'title':
				$this->meta->title ??= $content;

				return;
			case 'theme-color':
				if ($media = @$meta->attributes['media']) {
					$this->meta->themeColor[$media] = $content;
				} else {
					$this->meta->themeColor[] = $content;
				}

				return;
		}

		// if (preg_match("/^twitter\:(.*)/i", $name, $matches)) {
		// 	$this->meta->twitter[$matches[1]] = $content;

		// 	return;
		// }
	}

	protected function handleMetaWithProperty(string $property, Element $meta): void
	{
		// $property = mb_strtolower(trim($property), 'UTF-8');

		// if (!$content = @$meta->attributes['content']) {
		// 	return;
		// }

		// if ($this->ogDistributor->set($property, $content)) {
		// 	return;
		// }

		// if (preg_match("/^twitter\:(.*)/i", $property, $matches)) {
		// 	$this->meta->twitter[$matches[1]] = @$meta->attributes['content'];
		// 	unset($matches);

		// 	return;
		// }
	}
}
