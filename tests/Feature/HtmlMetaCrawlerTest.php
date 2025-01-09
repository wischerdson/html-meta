<?php

namespace Tests\Feature;

use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Osmuhin\HtmlMeta\Crawler;
use Osmuhin\HtmlMeta\Dto\Meta;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;

class HtmlMetaCrawlerTest extends TestCase
{
	public function test_fetching_via_url(): void
	{
		$meta = Crawler::init(url: 'https://github.com')->run();

		assertInstanceOf(Meta::class, $meta);
	}

	public function test_fetching_via_guzzle_request(): void
	{
		$request = new GuzzleRequest('GET', 'https://github.com');
		$meta = Crawler::init(request: $request)->run();

		assertInstanceOf(Meta::class, $meta);
	}

	public function test_parsing_base_meta(): void
	{
		$html = file_get_contents(__DIR__ . '/resources/meta.html');

		$meta = Crawler::init(html: $html)->run();

		assertSame('UTF-8', $meta->charset);
		assertSame('light dark', $meta->colorScheme);
		assertSame('ExampleApp', $meta->applicationName);
		assertSame('Evil Inc.', $meta->copyright);
		assertSame('Some description', $meta->description);
		assertSame('WordPress.com', $meta->generator);
		assertSame('en', $meta->lang);
		assertSame('origin', $meta->referrer);
		assertSame([
			0 => '#eb0c0c',
			'(prefers-color-scheme: dark)' => '#ff0'
		], $meta->themeColor);
		assertSame('Document', $meta->title);
		assertSame('width=device-width, initial-scale=1.0', $meta->viewport);
		assertSame('money, exchange', $meta->keywords);
		assertSame('noindex', $meta->robots);
		assertSame('yes', $meta->appleMobileWebAppCapable);
		assertSame('black', $meta->appleMobileWebAppStatusBarStyle);
		assertSame('telephone=no', $meta->formatDetection);
		assertSame('Osmuhin', $meta->author);
		assertSame('rtl', $meta->dir);
		assertSame('app-id=myAppStoreID, app-argument=myURL', $meta->appleItunesApp);
		assertSame([
			'lang' => 'en',
			'data-theme' => 'dark',
			'dir' => 'rtl'
		], $meta->htmlAttributes);
	}

	public function test_parsing_http_equiv(): void
	{
		$html = file_get_contents(__DIR__ . '/resources/http-equiv.html');

		$meta = Crawler::init(html: $html)->run();

		assertSame('text/html; charset=UTF-8', $meta->httpEquiv->contentType);
		assertSame('IE=edge', $meta->httpEquiv->xUaCompatible);
		assertSame('no-cache', $meta->httpEquiv->cacheControl);
		assertSame('en', $meta->httpEquiv->contentLanguage);
		assertSame('no-cache', $meta->httpEquiv->pragma);
		assertSame('Tue, January 01, 2025, 12:00:00 GMT', $meta->httpEquiv->expires);
		assertSame('5; url=https://example.com', $meta->httpEquiv->refresh);
		assertSame('default-src \'self\';', $meta->httpEquiv->contentSecurityPolicy);
		assertSame('on', $meta->httpEquiv->xDnsPrefetchControl);
		assertSame('*', $meta->httpEquiv->accessControlAllowOrigin);
		assertSame([
			'non-standart-meta' => 'some-value 2',
			'non-standart-meta-3' => 'some-value 3'
		], $meta->httpEquiv->other);
	}

	public function test_parsing_favicon(): void
	{
		$html = file_get_contents(__DIR__ . '/resources/favicon.html');

		$meta = Crawler::init(html: $html, url: 'http://example.com/path')->run();

		assertSame([
			'manifest' => 'http://example.com/manifest.webmanifest',
			'icons' => [
				[
					'url' => 'http://example.com/path/favicon-small.ico',
					'mime' => 'application/ico',
					'extension' => 'ico',
					'width' => 16,
					'height' => 14,
					'sizes' => '16x14'
				],
				[
					'url' => 'http://example.com/favicon.ico',
					'mime' => 'application/ico',
					'extension' => 'ico',
					'width' => null,
					'height' => null,
					'sizes' => 'any'
				],
				[
					'url' => 'http://example.com/icon.xsvg',
					'mime' => 'image/svg+xml',
					'extension' => 'xsvg',
					'width' => null,
					'height' => null,
					'sizes' => null
				]
			],
			'appleTouchIcons' => [
				[
					'url' => 'apple.com/apple-touch-icon.png',
					'mime' => 'image/png',
					'extension' => 'png',
					'width' => null,
					'height' => null,
					'sizes' => null
				]
			],
		], $meta->favicon->toArray());
	}
}
