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
			'content-type' => 'contentType',
			'x-ua-compatible' => 'xUaCompatible',
			'cache-control' => 'cacheControl',
			'content-language' => 'contentLanguage',
			'pragma' => 'pragma',
			'expires' => 'expires',
			'refresh' => 'refresh',
			'content-security-policy' => 'contentSecurityPolicy',
			'x-dns-prefetch-control' => 'xDnsPrefetchControl',
			'access-control-allow-origin' => 'accessControlAllowOrigin',
		];
	}
}
