<?php

namespace Tests\Distributors;

use Osmuhin\HtmlMeta\Distributors\MetaDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionProperty;
use Tests\Traits\ElementCreator;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

final class MetaDistributorTest extends TestCase
{
	use ElementCreator;

	private Meta $meta;

	private MetaDistributor $distributor;

	protected function setUp(): void
	{
		$this->meta = new Meta();
		$this->distributor = new MetaDistributor();
		$this->distributor->setMeta($this->meta);
	}

	#[Test]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method(): void
	{
		$element = self::makeElement('title', innerText: 'Hello world');
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeElement('meta');
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeElement('meta', ['charset' => 'UTF-8']);
		self::assertTrue($this->distributor->canHandle($element));
	}

	#[Test]
	#[TestDox('Can distributor fills Meta DTO by the map')]
	public function test_can_distributor_fills_dto_by_the_map(): void
	{
		$map = (new ReflectionMethod($this->distributor, 'getPropertiesMap'))->invoke(null);

		foreach ($map as $propertyInTag => $propertyInObject) {
			$content1 = "Some content for the property {$propertyInTag}";
			$content2 = "Duplicate property {$propertyInTag} with another content";
			$element1 = self::makeNamedMetaElement($propertyInTag, $content1);
			$element2 = self::makeNamedMetaElement($propertyInTag, $content2);

			$this->distributor->handle($element1);
			$this->distributor->handle($element2);

			self::assertSame($content1, $this->meta->$propertyInObject);
		}
	}

	public function test_can_distributor_handles_charset(): void
	{
		$element = self::makeElement('meta', ['charset' => 'CP1251']);
		$this->distributor->handle($element);

		assertEquals('CP1251', $this->meta->charset);
	}

	public function test_can_distributor_handles_empty_content(): void
	{
		$element = self::makeNamedMetaElement('meta', "  \n   ");
		$this->distributor->handle($element);

		$reflection = new ReflectionProperty($this->distributor, 'testEmptyContent');
		$reflection->setAccessible(true);

		assertTrue($reflection->getValue($this->distributor));
	}

	public function test_how_distributor_fills_title(): void
	{
		$this->meta->title = 'Test123';

		$element = self::makeNamedMetaElement('title', '123Test');
		$this->distributor->canHandle($element);
		$this->distributor->handle($element);

		self::assertSame('Test123', $this->meta->title);

		$this->meta->title = null;

		$element = self::makeNamedMetaElement('title', '12345Test');
		$this->distributor->canHandle($element);
		$this->distributor->handle($element);

		self::assertSame('12345Test', $this->meta->title);
	}

	public function test_theme_color(): void
	{
		$element = self::makeNamedMetaElement('theme-color', 'cyan');
		$this->distributor->canHandle($element);
		$this->distributor->handle($element);

		self::assertSame([0 => 'cyan'], $this->meta->themeColor);

		$element = self::makeNamedMetaElement('theme-color', '#fff', ['media' => '(prefers-color-scheme: light)']);
		$this->distributor->canHandle($element);
		$this->distributor->handle($element);

		self::assertSame([
			0 => 'cyan',
			'(prefers-color-scheme: light)' => '#fff'
		], $this->meta->themeColor);
	}
}
