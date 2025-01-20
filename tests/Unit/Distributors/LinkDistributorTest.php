<?php

namespace Tests\Unit\Distributors;

use Osmuhin\HtmlMeta\Distributors\LinkDistributor;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Traits\ElementCreator;
use Tests\Unit\Traits\SetupContainer;

use function PHPUnit\Framework\assertSame;

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

	public function test_canonical(): void
	{
		$element = self::makeElement('link', ['rel' => 'caNonicAl   ', 'href' => "\n\n /catalog/123   "]);

		$this->distributor->handle($element);

		assertSame('/catalog/123', $this->meta->canonical);
	}
}
