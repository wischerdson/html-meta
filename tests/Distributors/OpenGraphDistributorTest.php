<?php

namespace Tests\Distributors;

use Osmuhin\HtmlMeta\DataMappers\OpenGraphDataMapper;
use Osmuhin\HtmlMeta\Distributors\OpenGraphDistributor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Traits\DataMapperInjector;
use Tests\Traits\ElementCreator;
use Tests\Traits\SetupContainer;

final class OpenGraphDistributorTest extends TestCase
{
	use ElementCreator, SetupContainer, DataMapperInjector;

	private OpenGraphDistributor $distributor;

	protected function setUp(): void
	{
		$this->distributor = new OpenGraphDistributor();
	}

	public static function metaPropertiesProvider(): array
	{
		return [
			[null, "123", false],
			['baz', null, false],
			['', '', false],
			[" \t  ", "\n\t  ", false],
			[" \t  ", 'foo', false],
			['bar', "\n\t  ", false],
			['bar', "123123", false],
			['foo:bar', "123123", false],
			['og', "123123", false],
			['og:title', "123123", true],
		];
	}

	public static function assigningInDataMapperProvider(): array
	{
		return [
			// method, asNew, key, content
			['assignImage', true, 'og:image', '/storage/picture.png'],
			['assignImage', true, 'og:image:url', '/storage/another-picture.jpg'],
			['assignImage', false, 'og:image:secure_url', 'https://example.com/storage/another-picture.jpg'],
			['assignImage', false, 'og:image:type', 'image/pjpeg'],
			['assignImage', false, 'og:image:width', '200'],
			['assignImage', false, 'og:image:height', '400'],
			['assignImage', false, 'og:image:alt', 'Picture with "png" extension'],

			['assignVideo', true, 'og:video', '/storage/video.mov'],
			['assignVideo', true, 'og:video:url', '/storage/another-video.mp4'],
			['assignVideo', false, 'og:video:secure_url', 'https://example.com/storage/another-video.mp4'],
			['assignVideo', false, 'og:video:type', 'video/mp4'],
			['assignVideo', false, 'og:video:width', '80dfgdf0'],
			['assignVideo', false, 'og:video:height', '1000'],

			['assignAudio', true, 'og:audio', '/storage/audio.mp3'],
			['assignAudio', true, 'og:audio:url', '/storage/another-audio.wav'],
			['assignAudio', false, 'og:audio:secure_url', 'https://example.com/storage/another-audio.wav'],
			['assignAudio', false, 'og:audio:type', 'audio/wav']
		];
	}

	#[DataProvider('metaPropertiesProvider')]
	public function test_can_handle_method(?string $property, ?string $value, bool $expected): void
	{
		$element = self::makeMetaWithProperty($property, $value);
		self::assertSame($expected, $this->distributor->canHandle($element));
	}

	public function test_handle_method_uses_data_mapper(): void
	{
		$dataMapper = self::createMock(OpenGraphDataMapper::class);

		$dataMapper->expects($this->once())
			->method('assign')
			->with($this->identicalTo('og:title'), $this->identicalTo('value1'))
			->willReturn(true);

		self::injectDataMapper($this->distributor, $dataMapper);

		$element = self::makeMetaWithProperty('og:title', 'value1');

		$this->distributor->canHandle($element);
		$this->distributor->handle($element);
	}

	#[DataProvider('assigningInDataMapperProvider')]
	public function test_handle_method_uses_data_mapper_objects_assigning(string $method, bool $asNew, string $key, string $content): void
	{
		$dataMapper = self::createMock(OpenGraphDataMapper::class);

		$dataMapper->expects($this->once())
			->method($method)
			->with($this->identicalTo($key), $this->identicalTo($content), $this->identicalTo($asNew))
			->willReturn(true);

		self::injectDataMapper($this->distributor, $dataMapper);

		$element = self::makeMetaWithProperty($key, $content);

		$this->distributor->canHandle($element);
		$this->distributor->handle($element);
	}

	public function test_og_alternate_locales(): void
	{
		$element1 = self::makeMetaWithProperty('og:locale:alternate', 'fr_FR');
		$element2 = self::makeMetaWithProperty('og:locale:alternate', 'es_ES');

		$this->distributor->canHandle($element1);
		$this->distributor->handle($element1);

		$this->distributor->canHandle($element2);
		$this->distributor->handle($element2);

		self::assertSame(['fr_FR', 'es_ES'], $this->meta->openGraph->alternateLocales);
	}
}
