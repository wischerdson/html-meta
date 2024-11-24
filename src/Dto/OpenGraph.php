<?php

namespace Osmuhin\HtmlMetaCrawler\Dto;

use Osmuhin\HtmlMetaCrawler\Contracts\Dto;

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

	public ?string $image = null;

	public ?string $url = null;

	public ?string $description = null;

	public ?string $determiner = null;

	public ?string $siteName = null;

	public ?string $primaryLocale = null;

	/** @var string[] */
	public array $alternateLocales = [];

	/** @var \Osmuhin\HtmlMetaCrawler\Dto\OpenGraph\Image[] */
	public array $images = [];

	public function toArray(): array
	{
		return [

		];
	}
}
