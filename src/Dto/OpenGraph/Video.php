<?php

namespace Osmuhin\HtmlMeta\Dto\OpenGraph;

use Osmuhin\HtmlMeta\Contracts\Dto;

class Video implements Dto
{
	public ?string $url = null;

	public ?string $secureUrl = null;

	public ?string $type = null;

	public ?int $width = null;

	public ?int $height = null;

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
}
