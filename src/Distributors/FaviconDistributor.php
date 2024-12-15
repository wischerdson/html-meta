<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Dto\Icon;
use Osmuhin\HtmlMeta\Element;
use Osmuhin\HtmlMeta\MimeTypeGuesser;

class FaviconDistributor extends AbstractDistributor
{
	protected string $rel;

	protected string $href;

	public function canHandle(Element $el): bool
	{
		if (
			(!$rel = @$el->attributes['rel']) ||
			(!$href = @$el->attributes['href'])
		) {
			return false;
		}

		if (
			(!$rel = mb_strtolower(trim($rel), 'UTF-8')) ||
			(!$href = trim($href))
		) {
			return false;
		}

		$this->rel = $rel;
		$this->href = $href;

		return true;
	}

	public function handle(Element $el): void
	{
		switch ($this->rel) {
			case 'shortcut icon':
			case 'icon':
				$this->meta->favicon->icons[] = $this->makeIcon($el);
				break;
			case 'apple-touch-icon':
				$this->meta->favicon->appleTouchIcons[] = $this->makeIcon($el);
				break;
			case 'manifest':
				$this->meta->favicon->manifest = $this->href;
				break;
		}
	}

	protected function makeIcon(Element $el): Icon
	{
		$icon = new Icon();
		$icon->url = $this->href;
		$icon->extension = MimeTypeGuesser::guessExtension($this->href);
		$icon->mime = @$el->attributes['type'] ?: MimeTypeGuesser::guessMimeType($icon->extension);
		$icon->sizes = @$el->attributes['sizes'];

		if ($icon->sizes) {
			$explodedSizes = explode('x', $icon->sizes);

			if (\count($explodedSizes) === 2) {
				$icon->width = $explodedSizes[0];
				$icon->height = $explodedSizes[1];
			}
		}

		return $icon;
	}
}
