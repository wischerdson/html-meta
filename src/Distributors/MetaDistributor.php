<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;

class MetaDistributor extends AbstractDistributor
{
	public function canHandle(Element $el): bool
	{
		return $el->name === 'meta';
	}

	public function handle(Element $el): void
	{
		if ($this->meta->charset = @$el->attributes['charset']) {
			return;
		}
	}
}
