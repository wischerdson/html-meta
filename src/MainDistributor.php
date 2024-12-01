<?php

namespace Osmuhin\HtmlMeta;

use Osmuhin\HtmlMeta\Distributors\AbstractDistributor;
use Osmuhin\HtmlMeta\Distributors\HtmlDistributor;
use Osmuhin\HtmlMeta\Distributors\LinkDistributor;
use Osmuhin\HtmlMeta\Distributors\MetaDistributor;
use Osmuhin\HtmlMeta\Distributors\TitleDistributor;

class MainDistributor extends AbstractDistributor
{
	public function __construct()
	{
		$this->subDistributor(new HtmlDistributor())
			->subDistributor(new TitleDistributor())
			->subDistributor(new MetaDistributor())
			->subDistributor(new LinkDistributor());
	}

	public function canHandle(Element $element): bool
	{
		return true;
	}

	public function handle(Element $element): void
	{
		$this->pollSubDistributors($element);
	}
}
