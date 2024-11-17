<?php

namespace Osmuhin\HtmlMetaCrawler;

class ElementsCollection
{
	public ?Element $html = null;

	public ?Element $title = null;

	public array $meta = [];

	public array $link = [];

	public function setHtml(Element $html)
	{
		$this->html = $html;
	}

	public function setTitle(Element $title)
	{
		$this->title = $title;
	}

	public function addMeta(Element $meta)
	{
		$this->meta[] = $meta;
	}

	public function addLink(Element $link)
	{
		$this->link[] = $link;
	}
}
