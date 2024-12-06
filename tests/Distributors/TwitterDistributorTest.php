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

	public function setUp(): void
	{
		$this->meta = new Meta();
		$this->distibutor = new TwitterDistributor();
		$this->distibutor->setMeta($this->meta);
	}

	#[Test]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method()
	{
		$element = $this->makeElement('<meta />');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<meta name="    " content="    " />');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<meta property="    " content="    " />');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<meta property="twitterProperty" content="someContent" />');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<meta property="twitter:name" content="someContent" />');
		$this->assertTrue($this->distibutor->canHandle($element));

		$element = $this->makeElement('<meta name="twitter:name" content="someContent" />');
		$this->assertTrue($this->distibutor->canHandle($element));
	}

	#[Test]
	#[TestDox('Can distributor fills Meta DTO by the map')]
	public function test_can_distributor_fills_dto_by_the_map()
	{
		$map = (new ReflectionMethod($this->distibutor, 'getPropertiesMap'))->invoke(null);

		foreach ($map as $propertyInTag => $propertyInObject) {
			$element = $this->makeNamedMetaElement($propertyInTag, "  Some content for the property {$propertyInTag}  ");
			$duplicatedElement = $this->makeMetaElement(['http-equiv' => $propertyInTag, 'content' => "Duplicate property {$propertyInTag} with another content"]);
			// $element1 = $this->makeMetaElement(['http-equiv' => $propertyInTag, 'content' => "  Some content for the property {$propertyInTag}  "]);

			$this->assertTrue($this->distibutor->canHandle($element));
			$this->distibutor->handle($element);

			$this->assertTrue($this->distibutor->canHandle($duplicatedElement));
			$this->distibutor->handle($duplicatedElement);

			$this->assertEquals("Some content for the property {$propertyInTag}", $this->meta->twitter->$propertyInObject);
		}
	}
}
