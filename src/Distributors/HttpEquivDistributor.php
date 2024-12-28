<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;
use Osmuhin\HtmlMeta\Utils;

class HttpEquivDistributor extends AbstractDistributor
{
	protected string $name;

	protected string $content;

	public function canHandle(Element $el): bool
	{
		if (!$name = @$el->attributes['http-equiv']) {
			return false;
		}

		if (!$name = mb_strtolower(trim($name), 'UTF-8')) {
			return false;
		}

		$content = @$el->attributes['content'];

		if (!$content || !$content = trim($content)) {
			return false;
		}

		$this->name = $name;
		$this->content = $content;

		return true;
	}

	public function handle(Element $el): void
	{
		Utils::assignAccordingToTheMap(
			self::getPropertiesMap(),
			$this->meta->httpEquiv,
			$this->name,
			$this->content
		) || $this->meta->httpEquiv->other[$this->name] = $this->content;
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
