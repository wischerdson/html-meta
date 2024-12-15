<?php

namespace Tests\Distributors;

use Osmuhin\HtmlMeta\Distributors\TitleDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Tests\Traits\ElementCreator;

final class TitleDistributorTest extends TestCase
{
	use ElementCreator;

	private Meta $meta;

	private TitleDistributor $distibutor;

	protected function setUp(): void
	{
		$this->meta = new Meta();
		$this->distibutor = new TitleDistributor();
		$this->distibutor->setMeta($this->meta);
	}

	#[Test]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method()
	{
		$element = $this->makeElement('h1', innerText: 'Hello world');
		self::assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('title', innerText: 'Hello world');
		self::assertTrue($this->distibutor->canHandle($element));
	}

	#[Test]
	#[TestDox('Test "handle" method of the distributor')]
	public function test_handle_method()
	{
		$element = $this->makeElement('title', innerText: '123Hello world321');

		$this->distibutor->handle($element);
		self::assertSame('123Hello world321', $this->meta->title);
	}
}
