<?php

namespace Osmuhin\HtmlMeta\Dto\OpenGraph;

use Osmuhin\HtmlMeta\Contracts\Dto;

class VideoMovie implements Dto
{
	public ?string $actor = null;

	public ?string $actorRole = null;

	public ?string $director = null;

	public ?string $writer = null;

	public ?string $duration = null;

	public ?string $releaseDate = null;

	public ?string $tag = null;

	public function toArray(): array
	{
		return [
			'actor' => $this->actor,
			'actorRole' => $this->actorRole,
			'director' => $this->director,
			'writer' => $this->writer,
			'duration' => $this->duration,
			'releaseDate' => $this->releaseDate,
			'tag' => $this->tag
		];
	}
}
