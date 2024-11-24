<?php

namespace Osmuhin\HtmlMetaCrawler;

class Meta
{
	/**
	 * A charset declaration, giving the character encoding in which the document is encoded.
	 *
	 * <meta charset="utf-8">
	 */
	public ?string $charset = null;

	/**
	 * Specifies one or more color schemes with which the document is compatible.
	 *
	 * <meta name="color-scheme" content="light dark">
	 */
	public ?string $colorScheme = null;

	/**
	 * The name of the application running in the web page.
	 *
	 * <meta name="application-name" content="Amazon">
	 */
	public ?string $applicationName = null;

	/**
	 * A short and accurate summary of the content of the page.
	 *
	 * <meta name="description" content="Some description">
	 */
	public ?string $description = null;

	/**
	 * The name of the document's author.
	 *
	 * <meta name="author" content="Osmuhin Daniil">
	 */
	public ?string $author = null;

	/**
	 * The identifier of the software that generated the page.
	 *
	 * <meta name="generator" content="WordPress.com">
	 */
	public ?string $generator = null;

	/**
	 * The lang global attribute helps define the language of an element: the language that
	 * non-editable elements are written in, or the language that the editable elements should
	 * be written in by the user. The attribute contains a single "language tag" in the format
	 * defined in RFC 5646: Tags for Identifying Languages (also known as BCP 47).
	 *
	 * The default value of lang, which means that the language is unknown.
	 */
	public ?string $lang = null;

	/**
	 * Controls the HTTP Referer header of requests sent from the document.
	 *
	 * Values for the content attribute of <meta name="referrer">:
	 *
	 * - no-referrer: Do not send a HTTP Referer header;
	 * - origin: Send the origin (https://developer.mozilla.org/en-US/docs/Glossary/Origin)
	 * of the document;
	 * - no-referrer-when-downgrade: Send the full URL when the destination is at least as secure
	 * as the current page (HTTP(S)→HTTPS), but send no referrer when it's less secure
	 * (HTTPS→HTTP). This is the default behavior;
	 * - origin-when-cross-origin: Send the full URL (stripped of parameters) for same-origin
	 * requests, but only send the origin for other cases;
	 * - same-origin: Send the full URL (stripped of parameters) for same-origin requests.
	 * Cross-origin requests will contain no referrer header;
	 * - strict-origin: Send the origin when the destination is at least as secure as the current
	 * page (HTTP(S)→HTTPS), but send no referrer when it's less secure (HTTPS→HTTP);
	 * - strict-origin-when-cross-origin: Send the full URL (stripped of parameters) for
	 * same-origin requests. Send the origin when the destination is at least as secure as
	 * the current page (HTTP(S)→HTTPS). Otherwise, send no referrer;
	 * - unsafe-URL: Send the full URL (stripped of parameters) for same-origin or cross-origin
	 * requests.
	 */
	public ?string $referrer = null;

	/**
	 * Indicates a suggested color that user agents should use to customize the display of the
	 * page or of the surrounding user interface. The content attribute contains a valid CSS
	 * color.
	 */
	public array $themeColor = [];

	/**
	 * Document's title that is shown in a browser's title bar or a page's tab.
	 */
	public ?string $title = null;

	/**
	 * Viewport gives a browser instructions on how to control the page's dimensions and scaling.
	 */
	public ?string $viewport = null;

	/**
	 * Words relevant to the page's content separated by commas.
	 */
	public ?string $keywords = null;

	public array $htmlAttributes = [];

	public array $twitter = [];

	public Favicon $favicon;

	public OpenGraph $openGraph;

	/** @var \Osmuhin\HtmlMetaCrawler\Element[] */
	public array $unrecognizedMeta = [];

	public function __construct(private ElementsCollection $collection)
	{
		$this->openGraph = new OpenGraph();
		$this->favicon = new Favicon();

		$this->title = $this->collection->title?->innerText;
		$this->lang = @$this->collection->html?->attributes['lang'];

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

			if ($property = @$meta->attributes['property']) {
				if (preg_match("/^og\:/i", $property)) {
					dump('opengraph');
					continue;
				}

				if (preg_match("/^twitter\:(.*)/i", $property, $matches)) {
					$this->twitter[$matches[1]] = @$meta->attributes['content'];
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

	private function handleMetaName(string $name, Element $meta)
	{
		$name = mb_strtolower($name, 'UTF-8');
		$content = @$meta->attributes['content'];

		switch ($name) {
			case 'viewport':
				return $this->viewport = $content;
			case 'title':
				return $this->title ??= $content;
			case 'description':
				return $this->description = $content;
			case 'color-scheme':
				return $this->colorScheme = $content;
			case 'author':
				return $this->author = $content;
			case 'keywords':
				return $this->keywords = $content;
			case 'application-name':
				return $this->applicationName = $content;
			case 'generator':
				return $this->generator = $content;
			case 'referrer':
				return $this->referrer = $content;
			case 'theme-color':
				if ($media = @$meta->attributes['media']) {
					$this->themeColor[$media] = $content;
				} else {
					$this->themeColor[] = $content;
				}

				return $this->themeColor;
		}

		if (preg_match("/^twitter\:(.*)/i", $name, $matches)) {
			return $this->twitter[$matches[1]] = $content;
		}

		return false;
	}
}
