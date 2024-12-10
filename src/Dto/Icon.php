<?php

namespace Osmuhin\HtmlMeta\Dto;

use Osmuhin\HtmlMeta\Contracts\Dto;

class Icon implements Dto
{
	public string $url;

	public ?string $mime = null;

	public ?string $extension = null;

	public ?int $width = null;

	public ?int $height = null;

	public ?string $sizes = null;

	public function toArray(): array
	{
		return [
			'url' => $this->url,
			'mime' => $this->mime,
			'extension' => $this->extension,
			'width' => $this->width,
			'height' => $this->height,
			'sizes' => $this->sizes,
		];
	}
}
