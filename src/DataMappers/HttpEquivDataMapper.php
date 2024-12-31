<?php

namespace Osmuhin\HtmlMeta\DataMappers;

class HttpEquivDataMapper extends AbstractDataMapper
{
	protected static function getMap(): array
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

	public function assign(string $key, string $content)
	{
		return self::assignAccordingToTheMap(
			self::getMap(),
			$this->meta->httpEquiv,
			$key,
			$content
		);
	}
}
