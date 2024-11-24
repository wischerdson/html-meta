<?php

use Osmuhin\HtmlMetaCrawler\Crawler;
use Osmuhin\HtmlMetaCrawler\Meta;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class CrawlerTest extends TestCase
{
	#[Test]
	public function test_0(): void
	{
		$meta = Crawler::init(url: 'https://laravel.com')->run();

		dd($meta);
	}

	// #[Test]
	// public function test_100(): void
	// {
	// 	$node = new DOMNode();
	// }

// 	#[Test]
// 	#[TestDox('Будет ли ошибка, если передать неверную разметку')]
// 	public function test_1(): void
// 	{
// 		$meta = Crawler::init(html: '')->run();
// 		$this->assertInstanceOf(Meta::class, $meta);

// 		$meta = Crawler::init(html: '<html><body>')->run();
// 		$this->assertInstanceOf(Meta::class, $meta);

// 		$meta = Crawler::init(html: '<html></body>')->run();
// 		$this->assertInstanceOf(Meta::class, $meta);

// 		$meta = Crawler::init(html: '<html>')->run();
// 		$this->assertInstanceOf(Meta::class, $meta);

// 		$meta = Crawler::init(html: '<head></head>')->run();
// 		$this->assertInstanceOf(Meta::class, $meta);

// 		$meta = Crawler::init(html: '<body></body>')->run();
// 		$this->assertInstanceOf(Meta::class, $meta);
// 	}

// 	#[Test]
// 	#[TestDox('Можно ли получить язык документа')]
// 	public function test_2(): void
// 	{
// 		$html = <<<END
// <html lang="ru_RU">
// 	<head></head>
// 	<body></body>
// </html>
// END;

// 		$meta = Crawler::init(html: $html)->run();

// 		$this->assertSame('ru_RU', $meta->lang);

// 		$html = <<<END
// <html>
// 	<head></head>
// 	<body></body>
// </html>
// END;

// 		$meta = Crawler::init(html: $html)->run();

// 		$this->assertSame('en_US', $meta->lang);
// 	}

	// #[Test]
	// #[TestDox('Получим ли язык по умолчанию, если он не присутсвует в разметке')]
	// public function test_3(): void
	// {

	// }
}
