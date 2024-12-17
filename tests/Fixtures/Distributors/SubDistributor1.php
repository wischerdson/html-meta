<?php

namespace Tests\Fixtures\Distributors;

use Osmuhin\HtmlMeta\Distributors\AbstractDistributor;
use Osmuhin\HtmlMeta\Element;

class SubDistributor1 extends AbstractDistributor
{
	public function canHandle(Element $el): bool
	{
		return true;
	}

	public function handle(Element $el): void
	{

	}
}
