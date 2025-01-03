<?php

namespace Tests\Distributors;

use Osmuhin\HtmlMeta\DataMappers\TwitterDataMapper;
use Osmuhin\HtmlMeta\Distributors\TwitterDistributor;
use PHPUnit\Framework\TestCase;
use Tests\Traits\DataMapperInjector;
use Tests\Traits\ElementCreator;
use Tests\Traits\SetupContainer;

use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertSame;

final class TwitterDistributorTest extends TestCase
{
	use ElementCreator, SetupContainer, DataMapperInjector;

	private TwitterDistributor $distributor;

	protected function setUp(): void
	{
		$this->distributor = new TwitterDistributor();
	}

	public function test_can_handle_method(): void
	{
		$element = self::makeElement('meta');
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeNamedMetaElement('    ', '    ');
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeMetaWithProperty('    ', '    ');
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeMetaWithProperty('twitterProperty', 'someContent');
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeMetaWithProperty('twitter:name', '');
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeMetaWithProperty('twitter:name', '    ');
		self::assertFalse($this->distributor->canHandle($element));

		$element = self::makeMetaWithProperty('twitter:name', 'someContent');
		self::assertTrue($this->distributor->canHandle($element));

		$element = self::makeNamedMetaElement('twitter:name', 'someContent');
		self::assertTrue($this->distributor->canHandle($element));
	}

	public function test_handle_method_uses_data_mapper(): void
	{
		$dataMapper = self::createMock(TwitterDataMapper::class);

		$dataMapper->expects($this->once())
			->method('assign')
			->with($this->identicalTo('twitter:card'), $this->identicalTo('summary_large_image'))
			->willReturn(true);

		self::injectDataMapper($this->distributor, $dataMapper);

		$element = self::makeMetaWithProperty('twitter:card', 'summary_large_image');

		$this->distributor->canHandle($element);
		$this->distributor->handle($element);

		assertEmpty($this->meta->twitter->other);
	}

	public function test_handle_method_write_other_property_of_dto(): void
	{
		$dataMapper = self::createMock(TwitterDataMapper::class);

		$dataMapper->expects($this->once())
			->method('assign')
			->with($this->identicalTo('twitter:app:id:iphone', '123456789'))
			->willReturn(false);

		self::injectDataMapper($this->distributor, $dataMapper);

		$element = self::makeMetaWithProperty('twitter:app:id:iphone', '123456789');

		$this->distributor->canHandle($element);
		$this->distributor->handle($element);

		assertSame(['twitter:app:id:iphone' => '123456789'], $this->meta->twitter->other);
	}
}
