<?php

namespace Tests\Unit\Fixtures\Distributors;

use Osmuhin\HtmlMeta\Distributors\AbstractDistributor;

class SubDistributor3 extends AbstractDistributor
{
	public function canHandle(): bool
	{
		return true;
	}

	public function handle(): void
	{

	}
}
