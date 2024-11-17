<?php

use Osmuhin\HtmlMetaCrawler\Crawler;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CrawlerTest extends TestCase
{
    #[Test]
    public function greetsWithName(): void
    {
        Crawler::init(url: 'https://laravel.com')->run();

        // $this->assertSame('Hello', $hello);
    }
}
