<?php

namespace Tests\Feature\Fixtures;

use Osmuhin\HtmlMeta\Distributors\TitleDistributor;

class CustomTitleDistributor extends TitleDistributor
{
	public function handle(): void
	{
		$this->meta->title = $this->el->innerText . ' title suffix';
	}
}
