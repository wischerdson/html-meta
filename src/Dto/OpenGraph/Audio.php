<?php

namespace Osmuhin\HtmlMetaCrawler\Dto\OpenGraph;

use Osmuhin\HtmlMetaCrawler\Contracts\Dto;

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
}
