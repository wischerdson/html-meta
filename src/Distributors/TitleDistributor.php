<?php

namespace Osmuhin\HtmlMeta\Distributors;

class TitleDistributor extends AbstractDistributor
{
	public function canHandle(): bool
	{
		return $this->el->name === 'title';
	}

	public function handle(): void
	{
		$this->meta->title = $this->el->innerText;
	}
}
