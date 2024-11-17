<?php

namespace Osmuhin\HtmlMetaCrawler;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
	/** You can override DTO class your own. */
	public static string $metaDtoClass = Meta::class;

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

	public function run(): Meta
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
					break;
				case 'link':
					$this->collection->addLink(
						new Element($node)
					);
					break;
				case 'meta':
					$this->collection->addMeta(
						new Element($node)
					);
					break;
			}
		}

		return new self::$metaDtoClass($this->collection);
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
