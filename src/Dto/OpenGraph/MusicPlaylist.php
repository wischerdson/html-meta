<?php

namespace Osmuhin\HtmlMetaCrawler\Dto\OpenGraph;

use Osmuhin\HtmlMetaCrawler\Contracts\Dto;

class MusicPlaylist implements Dto
{
	public ?string $song = null;

	public ?string $songDisc = null;

	public ?string $songTrack = null;

	public ?string $creator = null;

	public function toArray(): array
	{
		return [
			'song' => $this->song,
			'songDisc' => $this->songDisc,
			'songTrack' => $this->songTrack,
			'creator' => $this->creator
		];
	}
}
