<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\DataMappers\OpenGraphDataMapper;
use Osmuhin\HtmlMeta\Dto\OpenGraph\Audio;
use Osmuhin\HtmlMeta\Dto\OpenGraph\Image;
use Osmuhin\HtmlMeta\Dto\OpenGraph\Video;
use Osmuhin\HtmlMeta\Element;

class OpenGraphDistributor extends AbstractDistributor
{
	protected string $handlerMethod;

	protected string $property;

	protected string $content;

	protected string $namespace;

	protected OpenGraphDataMapper $dataMapper;

	public function __construct()
	{
		parent::__construct();

		$this->dataMapper = new OpenGraphDataMapper();
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
		$this->{$this->handlerMethod}($this->property, $this->content);
	}

	protected function setOg(string $property, string $content): void
	{
		if ($this->dataMapper->assign($property, $content)) {
			return;
		}

		if (str_starts_with($property, 'og:image')) {
			$this->setOgImage($property, $content);

			return;
		}

		if ($property === 'og:locale:alternate') {
			$this->meta->openGraph->alternateLocales[] = $content;

			return;
		}

		if (str_starts_with($property, 'og:video')) {
			$this->setOgVideo($property, $content);

			return;
		}

		if (str_starts_with($property, 'og:audio')) {
			$this->setOgAudio($property, $content);

			return;
		}
	}

	protected function setOgImage(string $property, string $content)
	{
		if (str_ends_with($property, 'image') || str_ends_with($property, 'image:url')) {
			$image = new Image();
		} else {
			if (!$this->meta->openGraph->images) {
				return;
			}

			$image = array_pop($this->meta->openGraph->images);
		}

		$this->dataMapper->assignImage($image, $property, $content);

		$this->meta->openGraph->images[] = $image;
	}

	protected function setOgVideo(string $property, string $content)
	{
		if (str_ends_with($property, 'video') || str_ends_with($property, 'video:url')) {
			$video = new Video();
		} else {
			if (!$this->meta->openGraph->videos) {
				return;
			}

			$video = array_pop($this->meta->openGraph->videos);
		}

		$this->dataMapper->assignVideo($video, $property, $content);

		$this->meta->openGraph->videos[] = $video;
	}

	protected function setOgAudio(string $property, string $content)
	{
		if (str_ends_with($property, 'audio') || str_ends_with($property, 'audio:url')) {
			$audio = new Audio();
		} else {
			if (!$this->meta->openGraph->audio) {
				return;
			}

			$audio = array_pop($this->meta->openGraph->audio);
		}

		$this->dataMapper->assignAudio($audio, $property, $content);

		$this->meta->openGraph->audio[] = $audio;
	}
}
