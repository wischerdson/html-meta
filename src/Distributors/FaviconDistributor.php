<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\DataMappers\AbstractDataMapper;
use Osmuhin\HtmlMeta\Dto\Icon;
use Osmuhin\HtmlMeta\Element;
use Osmuhin\HtmlMeta\Utils;

class FaviconDistributor extends AbstractDistributor
{
	protected string $rel;

	protected string $href;

	protected AbstractDataMapper $dataMapper;

	public function __construct()
	{
		parent::__construct();

		$this->dataMapper = new class extends AbstractDataMapper {};
	}

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

		if ($this->config->shouldProcessUrls()) {
			$href = Utils::processUrl($href);
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
		$icon->extension = Utils::guessExtension($this->href);

		if ($icon->sizes = @$el->attributes['sizes']) {
			$icon->sizes = mb_strtolower(trim($icon->sizes), 'UTF-8');
			$explodedSizes = explode('x', $icon->sizes);

			if (\count($explodedSizes) === 2) {
				$this->dataMapper->assignPropertyWithObject(
					$icon,
					$this->dataMapper->int('width'),
					$explodedSizes[0]
				);

				$this->dataMapper->assignPropertyWithObject(
					$icon,
					$this->dataMapper->int('height'),
					$explodedSizes[1]
				);
			}
		}

		if ($icon->mime = @$el->attributes['type']) {
			$icon->mime = mb_strtolower(trim($icon->mime), 'UTF-8');
		} else {
			$icon->mime = Utils::guessMimeType($icon->extension);
		}

		return $icon;
	}
}
