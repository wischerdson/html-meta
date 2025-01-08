<?php

namespace Tests\Unit\Distributors;

use Osmuhin\HtmlMeta\DataMappers\HttpEquivDataMapper;
use Osmuhin\HtmlMeta\Distributors\HttpEquivDistributor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Traits\DataMapperInjector;
use Tests\Unit\Traits\ElementCreator;
use Tests\Unit\Traits\SetupContainer;

use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertSame;

final class HttpEquivDistributorTest extends TestCase
{
	use ElementCreator, SetupContainer, DataMapperInjector;

	private HttpEquivDistributor $distributor;

	protected function setUp(): void
	{
		$this->distributor = new HttpEquivDistributor();
	}

	public static function metaPropertiesProvider(): array
	{
		return [
			[[], false],
			[['charset' => 'UTF-8'], false],
			[['http-equiv' => null, 'content' => ''], false],
			[['http-equiv' => '', 'content' => ''], false],
			[['http-equiv' => '   '], false],
			[['http-equiv' => ' refresh  ', 'content' => null], false],
			[['http-equiv' => ' refresh  ', 'content' => " \n  "], false],
			[['http-equiv' => ' refresh  ', 'content' => " \nasd  "], true],
		];
	}

	#[DataProvider('metaPropertiesProvider')]
	public function test_can_handle_method(array $attributes, bool $expected): void
	{
		$element = self::makeElement('meta', $attributes);
		self::assertSame($expected, $this->distributor->canHandle($element));
	}

	public function test_handle_method_uses_data_mapper(): void
	{
		$dataMapper = self::createMock(HttpEquivDataMapper::class);

		$dataMapper->expects($this->once())
			->method('assign')
			->with($this->identicalTo('http-equiv-property'), $this->identicalTo('value1'))
			->willReturn(true);

		self::injectDataMapper($this->distributor, $dataMapper);

		$element = self::makeMetaElement(['http-equiv' => 'http-equiv-property', 'content' => 'value1']);

		$this->distributor->canHandle($element);
		$this->distributor->handle($element);

		assertEmpty($this->meta->httpEquiv->other);
	}

	public function test_handle_method_write_other_property_of_dto()
	{
		$dataMapper = self::createMock(HttpEquivDataMapper::class);

		$dataMapper->expects($this->once())
			->method('assign')
			->with($this->identicalTo('http-equiv-property', 'value2'))
			->willReturn(false);

		self::injectDataMapper($this->distributor, $dataMapper);

		$element = self::makeMetaElement(['http-equiv' => 'http-equiv-property', 'content' => 'value2']);

		$this->distributor->canHandle($element);
		$this->distributor->handle($element);

		assertSame(['http-equiv-property' => 'value2'], $this->meta->httpEquiv->other);
	}
}
