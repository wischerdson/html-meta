<?php

namespace Osmuhin\HtmlMeta\DataMappers;

class TwitterDataMapper extends AbstractDataMapper
{
	protected static function getMap(): array
	{
		return [
			'twitter:card' => 'card',
			'twitter:site' => 'site',
			'twitter:title' => 'title',
			'twitter:description' => 'description',
			'twitter:image' => self::url('image'),
			'twitter:image:alt' => 'imageAlt',
			'twitter:creator' => 'creator'
		];
	}

	public function assign(string $key, string $content): bool
	{
		return self::assignAccordingToTheMap(
			self::getMap(),
			$this->meta->twitter,
			$key,
			$content
		);
	}
}
