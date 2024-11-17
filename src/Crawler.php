<?php

namespace Osmuhin\HtmlMetaCrawler;

use DOMNode;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
	private string $url;

	private string $html;

	private ElementsCollection $collection;

	public function __construct()
	{
		$this->collection = new ElementsCollection();
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	public static function init(string $url = null, string $html = null)
	{	
		if ($url !== null && $html !== null) {
			throw new InvalidArgumentException('You cannot use both "url" and "html" arguments at the same time.');
		}
		
		$crawler = new self();
		$url ? $crawler->setUrl($url) : $crawler->setHtmlString($html);
		
		return $crawler;
	}

	public function setUrl(string $url): self
	{
		$this->url = $url;

		return $this;
	}

	public function setHtmlString(string $html): self
	{
		$this->html = $html;

		return $this;
	}

	public function run()
	{
		$this->checkHtmlForParsing();

		$crawler = new DomCrawler($this->html);

		if ($htmlNode = $crawler->filterXPath('//html')->getNode(0)) {
			$this->collection->setHtml(
				new Element($htmlNode)
			);
		}

		foreach ($crawler->filterXPath('//head/*') as $node) {
			switch ($node->nodeName) {
				case 'title':
					$this->collection->setTitle(
						new Element($node)
					);
				case 'link':
					$this->collection->addLink(
						new Element($node)
					);
				case 'meta':
					$this->collection->addMeta(
						new Element($node)
					);
			}
		}

		return new Meta($this->collection);
	}

	/**
	 * <html> HTML element
	 * See more: https://developer.mozilla.org/docs/Web/HTML/Element/html
	 */
	// public function setHtml(DOMNode $node)
	// {
	// 	foreach ($node->attributes as $attr) {
	// 		switch ($attr->nodeName) {
	// 			case 'lang':
	// 				$this->meta->lang = $attr->nodeValue;
	// 				break;
	// 			default:
	// 				$this->meta->htmlAttributes[$attr->nodeName] = $attr->nodeValue;
	// 		}
	// 	}
	// }

	/**
	 * <meta> HTML element
	 * See more: https://developer.mozilla.org/docs/Web/HTML/Element/meta
	 */
	// public function setMeta(DOMNode $node)
	// {
	// 	foreach ($node->attributes->getIterator() as $attr) {
	// 		switch ($attr->nodeName) {
	// 			case 'charset':
	// 				$this->meta->charset = $attr->nodeValue;
	// 				break;
	// 			case 'name':
	// 				$this->setMetaWithName;
	// 				$this->meta->charset = $attr->nodeValue;
	// 				break;
	// 			// default:
	// 				// $this->meta->htmlAttributes[$attr->nodeName] = $attr->nodeValue;
	// 		}
	// 	}
	// }

	/**
	 * <link> HTML element
	 * See more: https://developer.mozilla.org/docs/Web/HTML/Element/link
	 */
	public function setLink(DOMNode $node): void
	{

	}

	/**
	 * <base> HTML element
	 * See more: https://developer.mozilla.org/docs/Web/HTML/Element/base
	 */
	public function setBase(DOMNode $node)
	{
		
	}

	/**
	 * <title> HTML element
	 * See more: https://developer.mozilla.org/docs/Web/HTML/Element/title
	 */
	public function setTitle(DOMNode $node)
	{

	}

	/**
	 * @throws \RuntimeException
	 */
	private function checkHtmlForParsing()
	{
		if (isset($this->html)) {
			return;
		}

		if (!isset($this->url)) {
			throw new RuntimeException('Either an HTML string or a site URL must be provided for parsing.');
		}

		/** @todo needs to be rewritten */
		$this->html = file_get_contents($this->url);
	}
}
