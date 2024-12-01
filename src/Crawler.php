<?php

namespace Osmuhin\HtmlMeta;

use Osmuhin\HtmlMeta\Contracts\Distributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
	public static string $xpath = '//html|//html/head/link|//html/head/meta|//html/head/title';

	public static string $distributorClass = MainDistributor::class;

	private string $html;

	private Distributor $distributor;

	private Meta $meta;

	public function __construct()
	{
		$this->meta = new Meta();

		$this->distributor = new self::$distributorClass();
		$this->distributor->setMeta($this->meta);
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
	public function run()
	{
		if (!isset($this->html)) {
			throw new RuntimeException('An HTML string must be provided for parsing.');
		}

		$crawler = new DomCrawler($this->html);

		foreach ($crawler->filterXPath(self::$xpath) as $node) {
			$this->distributor->handle(
				new Element($node)
			);
		}

		return $this->meta;
	}
}
