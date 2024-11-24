<?php

namespace Osmuhin\HtmlMetaCrawler\Dto\OpenGraph;

use Osmuhin\HtmlMetaCrawler\Contracts\Dto;

class MusicRadioStation implements Dto
{
	public ?string $creator = null;

	public function toArray(): array
	{
		return [
			'creator' => $this->creator
		];
	}
}
