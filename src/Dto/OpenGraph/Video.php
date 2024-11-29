<?php

namespace Osmuhin\HtmlMeta\Dto\OpenGraph;

use Osmuhin\HtmlMeta\Contracts\Dto;

class Video implements Dto
{
	public ?string $url = null;

	public ?string $secureUrl = null;

	public ?string $type = null;

	public ?string $width = null;

	public ?string $height = null;

	public function toArray(): array
	{
		return [
			'url' => $this->url,
			'secureUrl' => $this->secureUrl,
			'type' => $this->type,
			'width' => $this->width,
			'height' => $this->height
		];
	}

	public static function getPropertiesMap(): array
	{
		return [
			'og:video' => 'url',
			'og:video:url' => 'url',
			'og:video:secure_url' => 'secureUrl',
			'og:video:type' => 'type',
			'og:video:width' => 'width',
			'og:video:height' => 'height',
		];
	}
}
