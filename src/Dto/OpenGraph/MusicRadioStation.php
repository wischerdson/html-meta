<?php

namespace Osmuhin\HtmlMeta\Dto\OpenGraph;

use Osmuhin\HtmlMeta\Contracts\Dto;

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
