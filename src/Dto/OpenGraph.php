<?php

namespace Osmuhin\HtmlMeta\Dto;

use Osmuhin\HtmlMeta\Contracts\Dto;
use Osmuhin\HtmlMeta\Dto\OpenGraph\Image;

/**
 * Open Graph is an Internet protocol that was created by Facebook to standardize the use of
 * meta-data representing the content of a web page.
 *
 * This class contains all the standard properties described in the documentation.
 *
 * https://ogp.me
 */
class OpenGraph implements Dto
{
	public ?string $title = null;

	public ?string $type = null;

	public ?string $url = null;

	public ?string $description = null;

	public ?string $determiner = null;

	public ?string $siteName = null;

	public ?string $primaryLocale = null;

	/** @var string[] */
	public array $alternateLocales = [];

	/** @var \Osmuhin\HtmlMeta\Dto\OpenGraph\Image[] */
	public array $images = [];

	/** @var \Osmuhin\HtmlMeta\Dto\OpenGraph\Video[] */
	public array $videos = [];

	/** @var \Osmuhin\HtmlMeta\Dto\OpenGraph\Audio[] */
	public array $audio = [];

	public function toArray(): array
	{
		return [
			'title' => $this->title,
			'type' => $this->type,
			'url' => $this->url,
			'description' => $this->description,
			'determiner' => $this->determiner,
			'siteName' => $this->siteName,
			'primaryLocale' => $this->primaryLocale,
			'alternateLocales' => $this->alternateLocales,
			'images' => array_map(fn (Image $image) => $image->toArray(), $this->images)
		];
	}

	/**
	 * Map of the correspondence of opengraph <meta\> properties to object properties
	 */
	public static function getPropertiesMap(): array
	{
		return [
			'og:title' => 'title',
			'og:type' => 'type',
			'og:url' => 'url',
			'og:description' => 'description',
			'og:determiner' => 'determiner',
			'og:site_name' => 'siteName',
			'og:locale' => 'primaryLocale'
		];
	}
}
