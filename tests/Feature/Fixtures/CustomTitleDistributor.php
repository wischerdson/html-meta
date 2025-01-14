<?php

namespace Tests\Feature\Fixtures;

use Osmuhin\HtmlMeta\Distributors\TitleDistributor;
use Osmuhin\HtmlMeta\Element;

class CustomTitleDistributor extends TitleDistributor
{
	public function handle(Element $el): void
	{
		$this->meta->title = $el->innerText . ' title suffix';
	}
}
