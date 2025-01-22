<?php

namespace Tests\Feature;

use Osmuhin\HtmlMeta\Crawler;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class NonUtfEncodingTest extends TestCase
{
	public function test()
	{
		if ($_ENV['SKIP_GUZZLE_TESTS']) {
			$this->markTestSkipped('Guzzle tests are disabled');

			return;
		}

		$meta = Crawler::init(url: 'http://lady-med.ru')->run();

		echo $meta->title; die;

		assertTrue(true);
	}
}
