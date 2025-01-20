<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;
use Osmuhin\HtmlMeta\Utils;

class LinkDistributor extends AbstractDistributor
{
	public function canHandle(Element $el): bool
	{
		return $el->name === 'link' && $el->attributes;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function handle(Element $el): void
	{
		if (!$rel = @$el->attributes['rel']) {
			return;
		}

		$rel = mb_strtolower(trim($rel), 'UTF-8');
		$href = @$el->attributes['href'];

		if (
			$rel === 'canonical' &&
			$href &&
			$href = trim($href)
		) {
			if ($this->config->shouldProcessUrls()) {
				$href = Utils::processUrl($href);
			}

			$this->meta->canonical = $href;
		}
	}
}
