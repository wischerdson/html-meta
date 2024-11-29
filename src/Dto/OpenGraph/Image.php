<?php

namespace Osmuhin\HtmlMetaCrawler\Dto\OpenGraph;

use Osmuhin\HtmlMetaCrawler\Contracts\Dto;

class Image implements Dto
{
	public ?string $url = null;

	public ?string $secureUrl = null;

	public ?string $type = null;

	public ?string $width = null;

	public ?string $height = null;

	public ?string $alt = null;

	public function toArray(): array
	{
		return [
			'url' => $this->url,
			'secureUrl' => $this->secureUrl,
			'type' => $this->type,
			'width' => $this->width,
			'height' => $this->height,
			'alt' => $this->alt,
		];
	}

	public static function getPropertiesMap(): array
	{
		return [
			'og:image' => 'url',
			'og:image:url' => 'url',
			'og:image:secure_url' => 'secureUrl',
			'og:image:type' => 'type',
			'og:image:width' => 'width',
			'og:image:height' => 'height',
			'og:image:alt' => 'alt'
		];
	}
}
