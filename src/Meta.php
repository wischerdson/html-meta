<?php

namespace Osmuhin\HtmlMetaCrawler;

class Meta
{
	public ?string $charset = null;

	public ?string $colorScheme = null;

	public ?string $description = null;

	public ?string $author = null;

	public ?string $lang = 'en_US';

	public ?string $msApplicationConfig = null;

	public ?string $msApplicationTileColor = null;

	public ?string $referrer = null;

	public ?string $themeColor = null;

	public ?string $title = null;

	public ?string $viewport = null;

	public ?string $contentSecurityPolicy = null;

	public ?string $contentType = null;

	public ?string $defaultStyle = null;

	public ?string $refresh = null;

	public ?string $applicationName = null;

	public ?string $generator = null;

	public ?string $keywords = null;

	public array $htmlAttributes = [];

	public Favicon $favicon;

	public OpenGraph $openGraph;

	public Twitter $twitter;

	/** @var \Osmuhin\HtmlMetaCrawler\Element[] */
	public array $unrecognizedMeta = [];

	public function __construct(private ElementsCollection $collection)
	{
		$this->openGraph = new OpenGraph();
		$this->twitter = new Twitter();
		$this->favicon = new Favicon();

		$this->title = $this->collection->title?->innerText;
		$this->lang = @$this->collection->html?->attributes['lang'] ?: $this->lang;

		$this->htmlAttributes = $this->collection->html?->attributes ?: [];

		$this->iterateMetas($collection->meta);
		$this->iterateLinks($collection->link);

		unset($this->collection);
	}

	/**
	 * @param \Osmuhin\HtmlMetaCrawler\Element[] $metas
	 */
	private function iterateMetas(array $metas): void
	{
		foreach ($metas as $meta) {
			if ($charset = @$meta->attributes['charset']) {
				$this->charset = $charset;
				continue;
			}

			if (
				($name = @$meta->attributes['name']) &&
				$this->handleMetaName($name, $meta)
			) {
				continue;
			}

			if (
				($httpEquiv = @$meta->attributes['http-equiv']) &&
				$this->handleMetaHttpEquiv($httpEquiv, $meta)
			) {
				continue;
			}

			if ($property = @$meta->attributes['property']) {
				if (preg_match("/^og\:/i", $property)) {
					dump('opengraph');
					continue;
				}

				if (preg_match("/^twitter\:/i", $property)) {
					dump('twitter');
					continue;
				}
			}

			$this->unrecognizedMeta[] = $meta;
		}
	}

	/**
	 * @param \Osmuhin\HtmlMetaCrawler\Element[] $links
	 */
	private function iterateLinks(array $links): void
	{

	}

	private function handleMetaName(string $name, Element $meta) {
		switch (mb_strtolower($name)) {
			case 'viewport':
				return $this->viewport = @$meta->attributes['content'];
			case 'title':
				return $this->title ??= @$meta->attributes['content'];
			case 'description':
				return $this->description = @$meta->attributes['content'];
			case 'msapplication-TileColor':
				return $this->msApplicationTileColor = @$meta->attributes['content'];
			case 'msapplication-config':
				return $this->msApplicationConfig = @$meta->attributes['content'];
			case 'theme-color':
				return $this->themeColor = @$meta->attributes['content'];
			case 'color-scheme':
				return $this->colorScheme = @$meta->attributes['content'];
			case 'author':
				return $this->author = @$meta->attributes['content'];
			case 'application-name':
				return $this->applicationName = @$meta->attributes['content'];
			case 'generator':
				return $this->generator = @$meta->attributes['content'];
			case 'keywords':
				return $this->keywords = @$meta->attributes['content'];
		}

		return false;
	}

	private function handleMetaHttpEquiv(string $httpEquiv, Element $meta) {
		switch (mb_strtolower($httpEquiv)) {
			case 'content-security-policy':
				return $this->contentSecurityPolicy = @$meta->attributes['content'];
			case 'content-type':
				return $this->contentType ??= @$meta->attributes['content'];
			case 'default-style':
				return $this->defaultStyle = @$meta->attributes['content'];
			case 'refresh':
				return $this->refresh = @$meta->attributes['content'];
		}

		return false;
	}
}
