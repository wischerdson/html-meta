<?php

namespace Osmuhin\HtmlMeta\Distributors;

class LinkDistributor extends AbstractDistributor
{
	public function canHandle(): bool
	{
		return $this->el->name === 'link' && $this->el->attributes;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function handle(): void
	{

	}
}
