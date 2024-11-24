<?php

namespace Osmuhin\HtmlMetaCrawler;

class Distributor
{
	private Meta $meta;

	public function __construct()
	{
		$this->meta = new Meta();
	}

	public function setHtml(Element $html)
	{

	}

	public function setTitle(Element $title)
	{

	}

	public function setMeta(Element $meta)
	{

	}

	public function setLink(Element $link)
	{

	}

	public function getMeta(): Meta
	{
		return $this->meta;
	}
}
