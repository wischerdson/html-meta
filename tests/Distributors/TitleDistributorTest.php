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

	public function setUp(): void
	{
		$this->meta = new Meta();
		$this->distibutor = new TitleDistributor($this->meta);
		$this->distibutor->setMeta($this->meta);
	}

	#[Test]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method()
	{
		$element = $this->makeElement('<h1>Hello world</h1>');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<title>Hello world</title>');
		$this->assertTrue($this->distibutor->canHandle($element));
	}

	#[Test]
	#[TestDox('Test "handle" method of the distributor')]
	public function test_handle_method()
	{
		$element = $this->makeElement('<title>123Hello world321</title>');

		$this->distibutor->handle($element);
		$this->assertSame('123Hello world321', $this->meta->title);
	}
}
