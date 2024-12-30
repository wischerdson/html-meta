<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Dto\OpenGraph\Audio;
use Osmuhin\HtmlMeta\Dto\OpenGraph\Image;
use Osmuhin\HtmlMeta\Dto\OpenGraph\Video;
use Osmuhin\HtmlMeta\Element;
use Osmuhin\HtmlMeta\Utils;

class OpenGraphDistributor extends AbstractDistributor
{
	protected string $handlerMethod;

	protected string $property;

	protected string $content;

	protected string $namespace;

	public function canHandle(Element $el): bool
	{
		$property = @$el->attributes['property'];
		$content = @$el->attributes['content'];

		if (!$property || !$content) {
			return false;
		}

		if (
			(!$property = trim($property)) ||
			!str_contains($property, ':') ||
			(!$content = trim($content))
		) {
			return false;
		}

		$namespace = explode(':', $property, 2)[0];

		if (($map = self::getMethodsMap()) && isset($map[$namespace])) {
			$this->property = mb_strtolower($property, 'UTF-8');
			$this->content = $content;
			$this->namespace = $namespace;
			$this->handlerMethod = $map[$namespace];

			return true;
		}

		return false;
	}

	public function handle(Element $el): void
	{
		$this->{$this->handlerMethod}();
	}

	protected static function getMethodsMap(): array
	{
		return [
			'og' => 'setOg',
			// 'image' => 'setImage',
			// 'music' => 'setMusic',
			// 'video' => 'setVideo',
			// 'article' => 'setArticle',
			// 'book' => 'setBook',
			// 'profile' => 'setProfile'
		];
	}

	protected static function getOgPropertiesMap(): array
	{
		return [
			'og:title' => 'title',
			'og:type' => 'type',
			'og:url' => 'url',
			'og:description' => 'description',
			'og:determiner' => 'determiner',
			'og:site_name' => 'siteName',
			'og:locale' => 'locale'
		];
	}

	protected static function getOgImagePropertiesMap(): array
	{
		return [
			'og:image' => Utils::assignPropertyAndGuessMimeType('url'),
			'og:image:url' => Utils::assignPropertyAndGuessMimeType('url'),
			'og:image:secure_url' => Utils::assignPropertyAndGuessMimeType('secureUrl'),
			'og:image:type' => Utils::assignPropertyForceOverwrite('type'),
			'og:image:width' => Utils::assignPropertyIgnoringErrors('width'),
			'og:image:height' => Utils::assignPropertyIgnoringErrors('height'),
			'og:image:alt' => 'alt'
		];
	}

	protected static function getOgVideoPropertiesMap(): array
	{
		return [
			'og:video' => Utils::assignPropertyAndGuessMimeType('url'),
			'og:video:url' => Utils::assignPropertyAndGuessMimeType('url'),
			'og:video:secure_url' => Utils::assignPropertyAndGuessMimeType('secureUrl'),
			'og:video:type' => Utils::assignPropertyForceOverwrite('type'),
			'og:video:width' => Utils::assignPropertyIgnoringErrors('width'),
			'og:video:height' => Utils::assignPropertyIgnoringErrors('height')
		];
	}

	protected static function getOgAudioPropertiesMap(): array
	{
		return [
			'og:audio' => Utils::assignPropertyAndGuessMimeType('url'),
			'og:audio:url' => Utils::assignPropertyAndGuessMimeType('url'),
			'og:audio:secure_url' => Utils::assignPropertyAndGuessMimeType('secureUrl'),
			'og:audio:type' => Utils::assignPropertyForceOverwrite('type')
		];
	}

	protected function setOg(): void
	{
		$assignmentResult = Utils::assignAccordingToTheMap(
			self::getOgPropertiesMap(),
			$this->meta->openGraph,
			$this->property,
			$this->content
		);

		if ($assignmentResult) {
			return;
		}

		if (str_starts_with($this->property, 'og:image')) {
			$this->setOgImage();

			return;
		}

		if ($this->property === 'og:locale:alternate') {
			$this->meta->openGraph->alternateLocales[] = $this->content;

			return;
		}

		if (str_starts_with($this->property, 'og:video')) {
			$this->setOgVideo();

			return;
		}

		if (str_starts_with($this->property, 'og:audio')) {
			$this->setOgAudio();

			return;
		}
	}

	protected function setOgImage()
	{
		if (str_ends_with($this->property, 'image') || str_ends_with($this->property, 'image:url')) {
			$image = new Image();
		} else {
			if (!$this->meta->openGraph->images) {
				return;
			}

			$image = array_pop($this->meta->openGraph->images);
		}

		Utils::assignAccordingToTheMap(
			self::getOgImagePropertiesMap(),
			$image,
			$this->property,
			$this->content
		);

		$this->meta->openGraph->images[] = $image;
	}

	protected function setOgVideo()
	{
		if (str_ends_with($this->property, 'video') || str_ends_with($this->property, 'video:url')) {
			$video = new Video();
		} else {
			if (!$this->meta->openGraph->videos) {
				return;
			}

			$video = array_pop($this->meta->openGraph->videos);
		}

		Utils::assignAccordingToTheMap(
			self::getOgVideoPropertiesMap(),
			$video,
			$this->property,
			$this->content
		);

		$this->meta->openGraph->videos[] = $video;
	}

	protected function setOgAudio()
	{
		if (str_ends_with($this->property, 'audio') || str_ends_with($this->property, 'audio:url')) {
			$audio = new Audio();
		} else {
			if (!$this->meta->openGraph->audio) {
				return;
			}

			$audio = array_pop($this->meta->openGraph->audio);
		}

		Utils::assignAccordingToTheMap(
			self::getOgAudioPropertiesMap(),
			$audio,
			$this->property,
			$this->content
		);

		$this->meta->openGraph->audio[] = $audio;
	}
}
