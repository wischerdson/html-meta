<?php

namespace Osmuhin\HtmlMeta\DataMappers;

class TwitterDataMapper extends AbstractDataMapper
{
	protected function getMap(): array
	{
		return [
			'twitter:card' => 'card',
			'twitter:site' => 'site',
			'twitter:title' => 'title',
			'twitter:description' => 'description',
			'twitter:image' => $this->url('image'),
			'twitter:image:alt' => 'imageAlt',
			'twitter:creator' => 'creator'
		];
	}

	public function assign(string $key, string $content): bool
	{
		return $this->assignAccordingToTheMap(
			$this->getMap(),
			$this->meta->twitter,
			$key,
			$content
		);
	}
}
