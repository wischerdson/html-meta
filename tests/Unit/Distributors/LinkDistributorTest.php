<?php

namespace Tests\Unit\Distributors;

use Osmuhin\HtmlMeta\Distributors\LinkDistributor;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Traits\ElementCreator;
use Tests\Unit\Traits\SetupContainer;

final class LinkDistributorTest extends TestCase
{
	use ElementCreator, SetupContainer;

	private LinkDistributor $distributor;

	protected function setUp(): void
	{
		$this->distributor = new LinkDistributor();
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
