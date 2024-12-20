<?php

namespace Tests\Distributors;

use Osmuhin\HtmlMeta\Distributors\LinkDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Tests\Traits\ElementCreator;

final class LinkDistributorTest extends TestCase
{
	use ElementCreator;

	private Meta $meta;

	private LinkDistributor $distributor;

	protected function setUp(): void
	{
		$this->meta = new Meta();
		$this->distributor = new LinkDistributor();
		$this->distributor->setMeta($this->meta);
	}

	#[Test]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method(): void
	{
		$element = self::makeElement('h1', innerText: 'Hello world');
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeElement('link');
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeElement('link', ['some-attribute' => '']);
		self::assertTrue($this->distributor->canHandle($element));
	}
}
