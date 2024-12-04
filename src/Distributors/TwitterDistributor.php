<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;

class TwitterDistributor extends AbstractDistributor
{
	protected string $name;

	protected string $content;

	public function canHandle(Element $el): bool
	{
		$name = @$el->attributes['name'] ?: @$el->attributes['property'];

		if (!$name || !str_starts_with($name, 'twitter:')) {
			return false;
		}

		if (
			(!$content = @$el->attributes['content']) ||
			(!$content = trim($content))
		) {
			return false;
		}

		$this->name = trim($name);
		$this->content = $content;

		return true;
	}

	public function handle(Element $el): void
	{
		$assignmentResult = self::assignAccordingToTheMap(
			self::getPropertiesMap(),
			$this->meta->twitter,
			$this->name,
			$this->content
		);

		if ($assignmentResult) {
			return;
		}

		$this->meta->twitter->other[$this->name] = $this->content;
	}

	protected static function getPropertiesMap(): array
	{
		return [
			'twitter:card' => 'card',
			'twitter:site' => 'site',
			'twitter:title' => 'title',
			'twitter:description' => 'description',
			'twitter:image' => 'image',
			'twitter:image:alt' => 'imageAlt',
			'twitter:creator' => 'creator'
		];
	}
}
