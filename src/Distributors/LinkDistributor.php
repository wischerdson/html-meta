<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;

class LinkDistributor extends AbstractDistributor
{
	public function canHandle(Element $el): bool
	{
		return $el->name === 'link' && $el->attributes;
	}

	public function handle(Element $el): void
	{
		//
	}
}
