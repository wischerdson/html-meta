<?php

use Osmuhin\HtmlMeta\Crawler;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CrawlerTest extends TestCase
{
	#[Test]
	public function test_0(): void
	{
		$html = file_get_contents(__DIR__ . '/Fixtures/laravel.html');
		// $html = file_get_contents();

		Crawler::init(html: $html)->run();
	}
}
