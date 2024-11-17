<?php

namespace Osmuhin\HtmlMetaCrawler;

class Meta
{
	/**
	 * A charset declaration, giving the character encoding in which the document is encoded.
	 */
	public ?string $charset = null;

	/**
	 * Specifies one or more color schemes with which the document is compatible.
	 *
	 * The browser will use this information in tandem with the user's browser or device
	 * settings to determine what colors to use for everything from background and foregrounds
	 * to form controls and scrollbars. The primary use for <meta name="color-scheme"> is to
	 * indicate compatibility with—and order of preference for—light and dark color modes.
	 *
	 * The value of the content property for color-scheme may be one of the following:
	 *
	 * - normal: The document is unaware of color schemes and should be rendered using the
	 * default color palette;
	 * - light, dark, light dark, dark light: One or more color schemes supported by the
	 * document. Specifying the same color scheme more than once has the same effect as specifying
	 * it only once. Indicating multiple color schemes indicates that the first scheme is
	 * preferred by the document, but that the second specified scheme is acceptable if the
	 * user prefers it.
	 * - only light: Indicates that the document only supports light mode, with a light
	 * background and dark foreground colors. By specification, only dark is not valid,
	 * because forcing a document to render in dark mode when it isn't truly compatible with
	 * it can result in unreadable content; all major browsers default to light mode if not
	 * otherwise configured.
	 */
	public ?string $colorScheme = null;

	/**
	 * The name of the application running in the web page.
	 */
	public ?string $applicationName = null;

	/**
	 * A short and accurate summary of the content of the page. Search engines like Google
	 * may use this field to control the appearance of the webpage in the search result.
	 */
	public ?string $description = null;

	/**
	 * The name of the document's author.
	 */
	public ?string $author = null;

	/**
	 * The identifier of the software that generated the page.
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
	public ?string $referrer = 'no-referrer-when-downgrade';

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

	private function handleMetaName(string $name, Element $meta) {
		switch (mb_strtolower($name)) {
			case 'viewport':
				return $this->viewport = @$meta->attributes['content'];
			case 'title':
				return $this->title ??= @$meta->attributes['content'];
			case 'description':
				return $this->description = @$meta->attributes['content'];
			case 'theme-color':
				if ($media = @$meta->attributes['media']) {
					$this->themeColor[$media] = @$meta->attributes['content'];
				} else {
					$this->themeColor[] = @$meta->attributes['content'];
				}

				return $this->themeColor;
			case 'color-scheme':
				return $this->colorScheme = @$meta->attributes['content'];
			case 'author':
				return $this->author = @$meta->attributes['content'];
			case 'keywords':
				return $this->keywords = @$meta->attributes['content'];
			case 'application-name':
				return $this->applicationName = @$meta->attributes['content'];
			case 'generator':
				return $this->generator = @$meta->attributes['content'];
		}

		if (preg_match("/^twitter\:(.*)/i", $name, $matches)) {
			return $this->twitter[$matches[1]] = @$meta->attributes['content'];
		}

		return false;
	}
}
