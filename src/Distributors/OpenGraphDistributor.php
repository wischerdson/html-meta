<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Element;

class OpenGraphDistributor extends AbstractDistributor
{
	public function canHandle(Element $el): bool
	{
		return false;
	}

	public function handle(Element $el): void
	{

	}
}
