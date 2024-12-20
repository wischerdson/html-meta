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

	private HttpEquivDistributor $distributor;

	protected function setUp(): void
	{
		$this->meta = new Meta();
		$this->distributor = new HttpEquivDistributor();
		$this->distributor->setMeta($this->meta);
	}

	#[Test]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method(): void
	{
		$element = self::makeElement('meta');
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeElement('meta', ['charset' => 'UTF-8']);
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeElement('meta', ['http-equiv' => '', 'content' => '']);
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeElement('meta', ['http-equiv' => '   ']);
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeElement('meta', ['http-equiv' => ' refresh  ', 'content' => ' ']);
		self::assertFalse($this->distributor->canHandle($element));
	}

	#[Test]
	#[TestDox('Can distributor fills Meta DTO by the map')]
	public function test_can_distributor_fills_dto_by_the_map(): void
	{
		$map = (new ReflectionMethod($this->distributor, 'getPropertiesMap'))->invoke(null);

		foreach ($map as $propertyInTag => $propertyInObject) {
			$element1 = self::makeMetaElement(['http-equiv' => $propertyInTag, 'content' => "  Some content for the property {$propertyInTag}  "]);
			$element2 = self::makeMetaElement(['http-equiv' => $propertyInTag, 'content' => "Duplicate property {$propertyInTag} with another content"]);

			self::assertTrue($this->distributor->canHandle($element1));
			$this->distributor->handle($element1);

			self::assertTrue($this->distributor->canHandle($element2));
			$this->distributor->handle($element2);

			self::assertSame("Some content for the property {$propertyInTag}", $this->meta->httpEquiv->$propertyInObject);
		}
	}
}
