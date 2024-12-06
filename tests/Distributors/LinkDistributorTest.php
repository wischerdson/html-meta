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

	private LinkDistributor $distibutor;

	public function setUp(): void
	{
		$this->meta = new Meta();
		$this->distibutor = new LinkDistributor();
		$this->distibutor->setMeta($this->meta);
	}

	#[Test]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method()
	{
		$element = $this->makeElement('<h1>Hello world</h1>');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<link />');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<link some-attribute="" />');
		$this->assertTrue($this->distibutor->canHandle($element));
	}
}
