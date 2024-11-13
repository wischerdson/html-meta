<?php

use Osmuhin\HtmlMetaCrawler\HtmlMetaCrawler;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CrawlerTest extends TestCase
{
    #[Test]
    public function greetsWithName(): void
    {
        $crawler = new HtmlMetaCrawler();
		$hello = $crawler->sayHello();

        $this->assertSame('Hello', $hello);
    }
}
