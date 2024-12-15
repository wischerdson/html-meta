<?php

namespace Tests\Distributors;

use Osmuhin\HtmlMeta\Distributors\TwitterDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Tests\Traits\ElementCreator;

final class TwitterDistributorTest extends TestCase
{
	use ElementCreator;

	private Meta $meta;

	private TwitterDistributor $distibutor;

	protected function setUp(): void
	{
		$this->meta = new Meta();
		$this->distibutor = new TwitterDistributor();
		$this->distibutor->setMeta($this->meta);
	}

	#[Test]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method(): void
	{
		$element = $this->makeElement('meta');
		self::assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeNamedMetaElement('    ', '    ');
		self::assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeMetaWithProperty('    ', '    ');
		self::assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeMetaWithProperty('twitterProperty', 'someContent');
		self::assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeMetaWithProperty('twitter:name', '');
		self::assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeMetaWithProperty('twitter:name', '    ');
		self::assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeMetaWithProperty('twitter:name', 'someContent');
		self::assertTrue($this->distibutor->canHandle($element));

		$element = $this->makeNamedMetaElement('twitter:name', 'someContent');
		self::assertTrue($this->distibutor->canHandle($element));
	}

	#[Test]
	#[TestDox('Can distributor fills Meta DTO by the map')]
	public function test_can_distributor_fills_dto_by_the_map(): void
	{
		$map = (new ReflectionMethod($this->distibutor, 'getPropertiesMap'))->invoke(null);

		foreach ($map as $propertyInTag => $propertyInObject) {
			$element = $this->makeNamedMetaElement($propertyInTag, "  Some content for the property {$propertyInTag}  ");
			$duplicatedElement = $this->makeMetaWithProperty($propertyInTag, "Duplicate property {$propertyInTag} with another content");

			self::assertTrue($this->distibutor->canHandle($element));
			$this->distibutor->handle($element);

			self::assertTrue($this->distibutor->canHandle($duplicatedElement));
			$this->distibutor->handle($duplicatedElement);

			self::assertSame("Some content for the property {$propertyInTag}", $this->meta->twitter->$propertyInObject);
		}
	}

	public function test_can_distributor_fills_other_dto_property(): void
	{
		$element = $this->makeMetaWithProperty('twitter:app:id:iphone', '123456789');
		self::assertTrue($this->distibutor->canHandle($element));
		$this->distibutor->handle($element);

		self::assertSame(['twitter:app:id:iphone' => '123456789'], $this->meta->twitter->other);
	}
}
