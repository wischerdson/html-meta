<?php

namespace Tests\Unit\Distributors;

use Osmuhin\HtmlMeta\DataMappers\MetaDataMapper;
use Osmuhin\HtmlMeta\Distributors\MetaDistributor;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Tests\Unit\Traits\DataMapperInjector;
use Tests\Unit\Traits\ElementCreator;
use Tests\Unit\Traits\SetupContainer;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

final class MetaDistributorTest extends TestCase
{
	use ElementCreator, SetupContainer, DataMapperInjector;

	private MetaDistributor $distributor;

	protected function setUp(): void
	{
		$this->distributor = new MetaDistributor();
	}

	public function test_can_handle_method(): void
	{
		$element = self::makeElement('title', innerText: 'Hello world');
		self::assertFalse($this->distributor->canHandle());

		$element = self::makeElement('meta');
		self::assertFalse($this->distributor->canHandle());

		$element = self::makeElement('meta', ['charset' => 'UTF-8']);
		self::assertTrue($this->distributor->canHandle());
	}

	public function test_handle_method_uses_data_mapper(): void
	{
		$dataMapper = self::createMock(MetaDataMapper::class);

		$dataMapper->expects($this->once())
			->method('assign')
			->with($this->identicalTo('viewport'), $this->identicalTo('user-scalable=no, width=device-width, initial-scale=1.0'))
			->willReturn(true);

		self::injectDataMapper($this->distributor, $dataMapper);

		$element = self::makeNamedMetaElement('viewport', 'user-scalable=no, width=device-width, initial-scale=1.0');

		$this->distributor->handle();

		$reflection = new ReflectionProperty($this->distributor, 'testAssignment');
		$reflection->setAccessible(true);

		assertTrue($reflection->getValue($this->distributor));
	}

	public function test_can_distributor_handles_charset(): void
	{
		$element = self::makeElement('meta', ['charset' => 'CP1251']);
		$this->distributor->handle();

		assertEquals('CP1251', $this->meta->charset);
	}

	public function test_can_distributor_handles_empty_content(): void
	{
		$element = self::makeNamedMetaElement('meta', "  \n   ");
		$this->distributor->handle();

		$reflection = new ReflectionProperty($this->distributor, 'testEmptyContent');
		$reflection->setAccessible(true);

		assertTrue($reflection->getValue($this->distributor));
	}

	public function test_how_distributor_fills_title(): void
	{
		$this->meta->title = 'Test123';

		$element = self::makeNamedMetaElement('title', '123Test');
		$this->distributor->canHandle();
		$this->distributor->handle();

		self::assertSame('Test123', $this->meta->title);

		$this->meta->title = null;

		$element = self::makeNamedMetaElement('title', '12345Test');
		$this->distributor->canHandle();
		$this->distributor->handle();

		self::assertSame('12345Test', $this->meta->title);
	}

	public function test_theme_color(): void
	{
		$element = self::makeNamedMetaElement('theme-color', 'cyan');
		$this->distributor->canHandle();
		$this->distributor->handle();

		self::assertSame([0 => 'cyan'], $this->meta->themeColor);

		$element = self::makeNamedMetaElement('theme-color', '#fff', ['media' => '(prefers-color-scheme: light)']);
		$this->distributor->canHandle();
		$this->distributor->handle();

		self::assertSame([
			0 => 'cyan',
			'(prefers-color-scheme: light)' => '#fff'
		], $this->meta->themeColor);
	}
}
