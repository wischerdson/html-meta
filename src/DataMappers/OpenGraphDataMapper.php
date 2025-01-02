<?php

namespace Osmuhin\HtmlMeta\DataMappers;

use Osmuhin\HtmlMeta\Dto\OpenGraph\Audio;
use Osmuhin\HtmlMeta\Dto\OpenGraph\Image;
use Osmuhin\HtmlMeta\Dto\OpenGraph\Video;

class OpenGraphDataMapper extends AbstractDataMapper
{
	protected static function getMap(): array
	{
		return [
			'og:title' => 'title',
			'og:type' => 'type',
			'og:url' => self::url('url'),
			'og:description' => 'description',
			'og:determiner' => 'determiner',
			'og:site_name' => 'siteName',
			'og:locale' => 'locale'
		];
	}

	protected static function getImageMap(): array
	{
		return [
			'og:image' => self::guessMimeType(self::url('url')),
			'og:image:url' => self::guessMimeType(self::url('url')),
			'og:image:secure_url' => self::guessMimeType(self::url('secureUrl')),
			'og:image:type' => self::forceOverwrite('type'),
			'og:image:width' => self::int('width'),
			'og:image:height' => self::int('height'),
			'og:image:alt' => 'alt'
		];
	}

	protected static function getVideoMap(): array
	{
		return [
			'og:video' => self::guessMimeType(self::url('url')),
			'og:video:url' => self::guessMimeType(self::url('url')),
			'og:video:secure_url' => self::guessMimeType(self::url('secureUrl')),
			'og:video:type' => self::forceOverwrite('type'),
			'og:video:width' => self::int('width'),
			'og:video:height' => self::int('height')
		];
	}

	protected static function getAudioMap(): array
	{
		return [
			'og:audio' => self::guessMimeType(self::url('url')),
			'og:audio:url' => self::guessMimeType(self::url('url')),
			'og:audio:secure_url' => self::guessMimeType(self::url('secureUrl')),
			'og:audio:type' => self::forceOverwrite('type'),
		];
	}

	public function assign(string $key, string $content): bool
	{
		return self::assignAccordingToTheMap(
			self::getMap(),
			$this->meta->openGraph,
			$key,
			$content
		);
	}

	public function assignImage(Image $image, string $key, string $content): bool
	{
		return self::assignAccordingToTheMap(self::getImageMap(), $image, $key, $content);
	}

	public function assignVideo(Video $video, string $key, string $content): bool
	{
		return self::assignAccordingToTheMap(self::getVideoMap(), $video, $key, $content);
	}

	public function assignAudio(Audio $audio, string $key, string $content): bool
	{
		return self::assignAccordingToTheMap(self::getAudioMap(), $audio, $key, $content);
	}
}
