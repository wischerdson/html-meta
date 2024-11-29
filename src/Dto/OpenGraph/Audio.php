<?php

namespace Osmuhin\HtmlMeta\Dto\OpenGraph;

use Osmuhin\HtmlMeta\Contracts\Dto;

class Audio implements Dto
{
	public ?string $url = null;

	public ?string $secureUrl = null;

	public ?string $type = null;

	public function toArray(): array
	{
		return [
			'url' => $this->url,
			'secureUrl' => $this->secureUrl,
			'type' => $this->type
		];
	}

	public static function getPropertiesMap(): array
	{
		return [
			'og:audio' => 'url',
			'og:audio:url' => 'url',
			'og:audio:secure_url' => 'secureUrl',
			'og:audio:type' => 'type',
		];
	}
}
