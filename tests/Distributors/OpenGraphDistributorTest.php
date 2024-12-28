<?php

namespace Tests\Distributors;

use Osmuhin\HtmlMeta\Distributors\OpenGraphDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use Osmuhin\HtmlMeta\Dto\OpenGraph\Image;
use Osmuhin\HtmlMeta\Dto\OpenGraph\Video;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Tests\Traits\ElementCreator;

final class OpenGraphDistributorTest extends TestCase
{
	use ElementCreator;

	private Meta $meta;

	private OpenGraphDistributor $distributor;

	protected function setUp(): void
	{
		$this->meta = new Meta();
		$this->distributor = new OpenGraphDistributor();
		$this->distributor->setMeta($this->meta);
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

	#[DataProvider('metaPropertiesProvider')]
	#[TestDox('Test "canHandle" method of the distributor')]
	public function test_can_handle_method(?string $property, ?string $value, bool $expected): void
	{
		$element = self::makeMetaWithProperty($property, $value);
		self::assertSame($expected, $this->distributor->canHandle($element));
	}

	public function test_og_properties_map(): void
	{
		$map = (new ReflectionMethod($this->distributor, 'getOgPropertiesMap'))->invoke(null);

		foreach ($map as $propertyInTag => $propertyInObject) {
			$element = self::makeMetaWithProperty($propertyInTag, "  Some content for the property {$propertyInTag}  ");

			$this->distributor->canHandle($element);
			$this->distributor->handle($element);

			self::assertSame("Some content for the property {$propertyInTag}", $this->meta->openGraph->$propertyInObject);
		}
	}

	public function test_og_image(): void
	{
		$properties = [
			['property' => 'og:image', 'content' => '/storage/picture.png'],
			['property' => 'og:image:alt', 'content' => 'Picture with "png" extension'],
			['property' => 'og:image:url', 'content' => '/storage/another-picture.jpg'],
			['property' => 'og:image:secure_url', 'content' => 'https://example.com/storage/another-picture.jpg'],
			['property' => 'og:image:type', 'content' => 'image/pjpeg'],
			['property' => 'og:image:width', 'content' => '200'],
			['property' => 'og:image:height', 'content' => '400'],
			['property' => 'og:image:alt', 'content' => 'Another picture with "jpg" extension']
		];

		foreach ($properties as ['property' => $property, 'content' => $content]) {
			$element = self::makeMetaWithProperty($property, $content);

			$this->distributor->canHandle($element);
			$this->distributor->handle($element);
		}

		self::assertCount(2, $this->meta->openGraph->images);
		self::assertInstanceOf(Image::class, $image1 = $this->meta->openGraph->images[0]);
		self::assertInstanceOf(Image::class, $image2 = $this->meta->openGraph->images[1]);

		self::assertSame([
			'url' => '/storage/picture.png',
			'secureUrl' => null,
			'type' => 'image/png',
			'width' => null,
			'height' => null,
			'alt' => 'Picture with "png" extension',
		], $image1->toArray());

		self::assertSame([
			'url' => '/storage/another-picture.jpg',
			'secureUrl' => 'https://example.com/storage/another-picture.jpg',
			'type' => 'image/pjpeg',
			'width' => 200,
			'height' => 400,
			'alt' => 'Another picture with "jpg" extension',
		], $image2->toArray());
	}

	public function test_og_video(): void
	{
		$properties = [
			['property' => 'og:video', 'content' => '/storage/video.mov'],
			['property' => 'og:video:url', 'content' => '/storage/another-video.mp4'],
			['property' => 'og:video:secure_url', 'content' => 'https://example.com/storage/another-video.mp4'],
			['property' => 'og:video:type', 'content' => 'video/mp4'],
			['property' => 'og:video:width', 'content' => '80dfgdf0'],
			['property' => 'og:video:height', 'content' => '1000']
		];

		foreach ($properties as ['property' => $property, 'content' => $content]) {
			$element = self::makeMetaWithProperty($property, $content);

			$this->distributor->canHandle($element);
			$this->distributor->handle($element);
		}

		self::assertCount(2, $this->meta->openGraph->videos);
		self::assertInstanceOf(Video::class, $video1 = $this->meta->openGraph->videos[0]);
		self::assertInstanceOf(Video::class, $video2 = $this->meta->openGraph->videos[1]);

		self::assertSame([
			'url' => '/storage/video.mov',
			'secureUrl' => null,
			'type' => 'video/quicktime',
			'width' => null,
			'height' => null,
		], $video1->toArray());

		self::assertSame([
			'url' => '/storage/another-video.mp4',
			'secureUrl' => 'https://example.com/storage/another-video.mp4',
			'type' => 'video/mp4',
			'width' => null,
			'height' => 1000,
		], $video2->toArray());
	}
}
