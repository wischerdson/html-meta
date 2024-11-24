<?php

namespace Osmuhin\HtmlMetaCrawler\Dto\OpenGraph;

use Osmuhin\HtmlMetaCrawler\Contracts\Dto;

class MusicSong implements Dto
{
	public ?string $duration = null;

	public ?string $album = null;

	public ?string $albumDisc = null;

	public ?string $albumTrack = null;

	public ?string $musician = null;

	public function toArray(): array
	{
		return [
			'duration' => $this->duration,
			'album' => $this->album,
			'albumDisc' => $this->albumDisc,
			'albumTrack' => $this->albumTrack,
			'musician' => $this->musician,
		];
	}
}
