<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;

class MetaDistributor extends AbstractDistributor
{
	public function canHandle(Element $el): bool
	{
		return $el->name === 'meta' && $el->attributes;
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

	protected function handleNamedMeta(string $name, Element $meta): void
	{
		if (!$name = mb_strtolower(trim($name), 'UTF-8')) {
			return;
		}

		if (!$content = @$meta->attributes['content']) {
			return;
		}

		$assignmentResult = self::assignAccordingToTheMap(
			self::getPropertiesMap(),
			$this->meta,
			$name,
			$content
		);

		if ($assignmentResult) {
			return;
		}

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
	}
}
