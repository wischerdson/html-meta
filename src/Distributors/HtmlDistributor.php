<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;

class HtmlDistributor extends AbstractDistributor
{
	public function canHandle(Element $el): bool
	{
		return $el->name === 'html';
	}

	public function handle(Element $el): void
	{
		$this->meta->lang = @$el->attributes['lang'];
		$this->meta->dir = @$el->attributes['dir'];

		$this->meta->htmlAttributes = $el->attributes;
	}
}
