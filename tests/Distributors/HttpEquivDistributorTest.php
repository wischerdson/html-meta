<?php

namespace Tests\Distributors;

use Osmuhin\HtmlMeta\Distributors\HttpEquivDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Tests\Traits\ElementCreator;

final class HttpEquivDistributorTest extends TestCase
{
	use ElementCreator;

	private Meta $meta;

	private HttpEquivDistributor $distibutor;

	public function setUp(): void
	{
		$this->meta = new Meta();
		$this->distibutor = new HttpEquivDistributor($this->meta);
		$this->distibutor->setMeta($this->meta);
	}

	#[Test]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method()
	{
		$element = $this->makeElement('<meta />');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<meta charset="UTF-8" />');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<meta http-equiv="" content="" />');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<meta http-equiv="   " />');
		$this->assertFalse($this->distibutor->canHandle($element));

		$element = $this->makeElement('<meta http-equiv=" refresh  " content=" " />');
		$this->assertFalse($this->distibutor->canHandle($element));
	}

	#[Test]
	#[TestDox('Can distributor fills Meta DTO by the map')]
	public function test_can_distributor_fills_dto_by_the_map()
	{
		$map = (new ReflectionMethod($this->distibutor, 'getPropertiesMap'))->invoke(null);

		foreach ($map as $propertyInTag => $propertyInObject) {
			$element1 = $this->makeMetaElement(['http-equiv' => $propertyInTag, 'content' => "  Some content for the property {$propertyInTag}  "]);
			$element2 = $this->makeMetaElement(['http-equiv' => $propertyInTag, 'content' => "Duplicate property {$propertyInTag} with another content"]);

			$this->assertTrue($this->distibutor->canHandle($element1));
			$this->distibutor->handle($element1);

			$this->assertTrue($this->distibutor->canHandle($element2));
			$this->distibutor->handle($element2);

			$this->assertEquals("Some content for the property {$propertyInTag}", $this->meta->httpEquiv->$propertyInObject);
		}
	}
}
