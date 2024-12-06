<?php

use Osmuhin\HtmlMeta\Crawler;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CrawlerTest extends TestCase
{
	// #[Test]
	// public function test_0(): void
	// {
	// 	$html = file_get_contents(__DIR__ . '/Fixtures/laravel.html');
	// 	// $html = file_get_contents();

	// 	$meta = Crawler::init(html: $html)->run();

	// 	dd($meta);
	// }

	#[Test]
	public function test_crawler_without_html(): void
	{
		$crawler = new Crawler();

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('An HTML string must be provided for parsing.');

		$crawler->run();
	}
}
