<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\DataMappers\OpenGraphDataMapper;
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
			$this->dataMapper->assignImage(
				key: $property,
				content: $content,
				asNew: str_ends_with($property, 'image') || str_ends_with($property, 'image:url')
			);

			return;
		}

		if ($property === 'og:locale:alternate') {
			$this->meta->openGraph->alternateLocales[] = $content;

			return;
		}

		if (str_starts_with($property, 'og:video')) {
			$this->dataMapper->assignVideo(
				key: $property,
				content: $content,
				asNew: str_ends_with($property, 'video') || str_ends_with($property, 'video:url')
			);

			return;
		}

		if (str_starts_with($property, 'og:audio')) {
			$this->dataMapper->assignAudio(
				key: $property,
				content: $content,
				asNew: str_ends_with($property, 'audio') || str_ends_with($property, 'audio:url')
			);

			return;
		}
	}
}
