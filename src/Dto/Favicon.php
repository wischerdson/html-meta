<?php

namespace Osmuhin\HtmlMeta\Dto;

use Osmuhin\HtmlMeta\Contracts\Dto;

class Favicon implements Dto
{
	public ?string $manifest = null;

	/** @var \Osmuhin\HtmlMeta\Dto\Icon[] */
	public array $icons = [];

	/** @var \Osmuhin\HtmlMeta\Dto\Icon[] */
	public array $appleTouchIcons = [];

	public function toArray(): array
	{
		return [
			'manifest' => $this->manifest,
			'icons' => array_map(fn (Icon $icon) => $icon->toArray(), $this->icons),
			'appleTouchIcons' => array_map(fn (Icon $icon) => $icon->toArray(), $this->appleTouchIcons)
		];
	}
}
