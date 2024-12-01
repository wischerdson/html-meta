<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;

class TitleDistributor extends AbstractDistributor
{
	public function canHandle(Element $el): bool
	{
		return $el->name === 'title';
	}

	public function handle(Element $el): void
	{
		$this->meta->title = $el->innerText;
	}
}
