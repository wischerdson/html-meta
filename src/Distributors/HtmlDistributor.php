<?php

namespace Osmuhin\HtmlMeta\Distributors;

class HtmlDistributor extends AbstractDistributor
{
	public function canHandle(): bool
	{
		return $this->el->name === 'html';
	}

	public function handle(): void
	{
		$this->meta->lang = $this->elAttr('lang', lowercase: false);
		$this->meta->dir = $this->elAttr('dir');

		$this->meta->htmlAttributes = $this->el->attributes;
	}
}
