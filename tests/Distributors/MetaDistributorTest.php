<?php

namespace Tests\Distributors;

use Osmuhin\HtmlMeta\Distributors\MetaDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Tests\Traits\ElementCreator;

use function PHPUnit\Framework\assertEquals;

final class MetaDistributorTest extends TestCase
{
	use ElementCreator;

	private Meta $meta;

	private MetaDistributor $distibutor;

	protected function setUp(): void
	{
		$this->meta = new Meta();
		$this->distibutor = new MetaDistributor();
		$this->distibutor->setMeta($this->meta);
	}

	#[Test]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method(): void
	{
		$element = $this->makeElement('title', innerText: 'Hello world');
		self::assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('meta');
		self::assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('meta', ['charset' => 'UTF-8']);
		self::assertTrue($this->distibutor->canHandle($element));
	}

	#[Test]
	#[TestDox('Can distributor fills Meta DTO by the map')]
	public function test_can_distributor_fills_dto_by_the_map(): void
	{
		$map = (new ReflectionMethod($this->distibutor, 'getPropertiesMap'))->invoke(null);

		foreach ($map as $propertyInTag => $propertyInObject) {
			$content1 = "Some content for the property {$propertyInTag}";
			$content2 = "Duplicate property {$propertyInTag} with another content";
			$element1 = $this->makeNamedMetaElement($propertyInTag, $content1);
			$element2 = $this->makeNamedMetaElement($propertyInTag, $content2);

			$this->distibutor->handle($element1);
			$this->distibutor->handle($element2);

			self::assertSame($content1, $this->meta->$propertyInObject);
		}
	}

	public function test_can_distributor_handles_charset(): void
	{
		$element = $this->makeElement('meta', ['charset' => 'CP1251']);
		$this->distibutor->canHandle($element);
		$this->distibutor->handle($element);

		assertEquals('CP1251', $this->meta->charset);
	}

	public function test_how_distributor_fills_title(): void
	{
		$this->meta->title = 'Test123';

		$element = $this->makeNamedMetaElement('title', '123Test');
		$this->distibutor->canHandle($element);
		$this->distibutor->handle($element);

		self::assertSame('Test123', $this->meta->title);

		$this->meta->title = null;

		$element = $this->makeNamedMetaElement('title', '12345Test');
		$this->distibutor->canHandle($element);
		$this->distibutor->handle($element);

		self::assertSame('12345Test', $this->meta->title);
	}
}
