<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;

class TwitterDistributor extends AbstractDistributor
{
	public function canHandle(Element $el): bool
	{
		return false;
	}

	public function handle(Element $el): void
	{

	}
}
