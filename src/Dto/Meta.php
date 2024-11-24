<?php

namespace Osmuhin\HtmlMetaCrawler\Dto;

use Osmuhin\HtmlMetaCrawler\Contracts\Dto;

class Meta implements Dto
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

	public HttpEquiv $httpEquiv;

	/** @var \Osmuhin\HtmlMetaCrawler\Element[] */
	public array $unrecognizedMeta = [];

	public function __construct()
	{
		$this->openGraph = new OpenGraph();
		$this->favicon = new Favicon();
		$this->httpEquiv = new HttpEquiv();
	}

	public function toArray(): array
	{
		return [

		];
	}

	/**
	 * Map of the correspondence of meta tag properties to object properties
	 */
	public function getPropertiesMap(): array
	{
		return [
			'viewport' => 'viewport',
			'description' => 'description',
			'color-scheme' => 'colorScheme',
			'author' => 'author',
			'keywords' => 'keywords',
			'application-name' => 'applicationName',
			'generator' => 'generator',
			'referrer' => 'referrer',
		];
	}
}
