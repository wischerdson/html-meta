<?php

namespace Osmuhin\HtmlMetaCrawler;

class Meta
{
	public ?string $charset = null;

	public ?string $colorScheme = null;

	public ?string $description = null;

	public ?string $lang = 'en_US';

	public ?string $msApplicationConfig = null;

	public ?string $msApplicationTileColor = null;

	public ?string $referrer = null;

	public ?string $themeColor = null;

	public ?string $title = null;

	public ?string $viewport = null;

	public array $htmlAttributes = [];

	public Favicon $favicon;

	public OpenGraph $openGraph;

	public Twitter $twitter;

	/**
	 * @param Osmuhin\HtmlMetaCrawler\Element[] $meta
	 * @param Osmuhin\HtmlMetaCrawler\Element[] $link
	 */
	public function __construct(private ElementsCollection $collection)
	{
		$this->openGraph = new OpenGraph();
		$this->twitter = new Twitter();
		$this->favicon = new Favicon();
	}
}
