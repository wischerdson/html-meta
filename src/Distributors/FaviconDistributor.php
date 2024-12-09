<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;

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

	}
}
