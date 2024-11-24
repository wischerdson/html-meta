<?php

namespace Osmuhin\HtmlMetaCrawler;

use Osmuhin\HtmlMetaCrawler\Dto\Meta;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
	/**
	 * You can override distributor class your own.
	 * It should extends the \Osmuhin\HtmlMetaCrawler\Distributor class.
	 */
	public static string $distributorClass = Distributor::class;

	/**
	 * You can override dto-class of meta your own.
	 * It should extends the \Osmuhin\HtmlMetaCrawler\Dto\Meta class.
	 */
	public static string $metaClass = Meta::class;

	/** A string with raw html */
	private string $html;

	/** An instance of the distributor class that generates the final result */
	private Distributor $distributor;

	public function __construct()
	{
		$this->distributor = new self::$distributorClass();
	}

	public static function init(string $html = null): self
	{
		$crawler = new self();
		$crawler->html = $html;

		return $crawler;
	}

	public function setHtmlString(string $html): self
	{
		$this->html = $html;

		return $this;
	}

	/**
	 * @throws \RuntimeException
	 */
	public function run(): Meta
	{
		if (!isset($this->html)) {
			throw new RuntimeException('An HTML string must be provided for parsing.');
		}

		$crawler = new DomCrawler($this->html);

		if ($htmlNode = $crawler->filterXPath('//html')->getNode(0)) {
			$this->distributor->setHtml(
				new Element($htmlNode)
			);
		}

		foreach ($crawler->filterXPath('//head/*') as $node) {
			switch ($node->nodeName) {
				case 'title':
					$this->distributor->setTitle(
						new Element($node)
					);
					break;
				case 'link':
					$this->distributor->setLink(
						new Element($node)
					);
					break;
				case 'meta':
					$this->distributor->setMeta(
						new Element($node)
					);
					break;
			}
		}

		return $this->distributor->getMeta();
	}
}
