<?php

namespace Osmuhin\HtmlMeta\Dto\OpenGraph;

use Osmuhin\HtmlMeta\Contracts\Dto;

class Article implements Dto
{
	public ?string $publishedTime = null;

	public ?string $modifiedTime = null;

	public ?string $expirationTime = null;

	public ?string $author = null;

	public ?string $section = null;

	public ?string $tag = null;

	public function toArray(): array
	{
		return [
			'publishedTime' => $this->publishedTime,
			'modifiedTime' => $this->modifiedTime,
			'expirationTime' => $this->expirationTime,
			'author' => $this->author,
			'section' => $this->section,
			'tag' => $this->tag
		];
	}
}
