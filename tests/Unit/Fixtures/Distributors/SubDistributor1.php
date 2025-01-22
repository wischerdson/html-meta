<?php

namespace Tests\Unit\Fixtures\Distributors;

use Osmuhin\HtmlMeta\Distributors\AbstractDistributor;
use Osmuhin\HtmlMeta\Element;

class SubDistributor1 extends AbstractDistributor
{
	public function canHandle(): bool
	{
		return true;
	}

	public function handle(): void
	{

	}
}
