<?php

namespace Tests\Distributors;

use Osmuhin\HtmlMeta\Distributors\HtmlDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Tests\Traits\ElementCreator;

final class HtmlDistributorTest extends TestCase
{
	use ElementCreator;

	private Meta $meta;

	private HtmlDistributor $distibutor;

	public function setUp(): void
	{
		$this->meta = new Meta();
		$this->distibutor = new HtmlDistributor($this->meta);
		$this->distibutor->setMeta($this->meta);
	}

	#[Test]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method()
	{
		$element = $this->makeElement('<head><meta charset="UTF-8" /></head>');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<html><head><meta charset="UTF-8" /></head></html>');
		$this->assertTrue($this->distibutor->canHandle($element));
	}

	#[Test]
	public function test_handle_method()
	{
		$element = $this->makeElement('<html lang="ru_RU" dir="rtl" class="dark"></html>');

		$this->distibutor->handle($element);

		$this->assertSame('ru_RU', $this->meta->lang);
		$this->assertSame('rtl', $this->meta->dir);

		$this->assertCount(3, $this->meta->htmlAttributes);

		$this->assertArrayHasKey('lang', $this->meta->htmlAttributes);
		$this->assertArrayHasKey('dir', $this->meta->htmlAttributes);
		$this->assertArrayHasKey('class', $this->meta->htmlAttributes);

		$this->assertSame('ru_RU', $this->meta->htmlAttributes['lang']);
		$this->assertSame('rtl', $this->meta->htmlAttributes['dir']);
		$this->assertSame('dark', $this->meta->htmlAttributes['class']);
	}
}
