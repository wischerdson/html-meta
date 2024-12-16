<?php

namespace Osmuhin\HtmlMeta;

use Osmuhin\HtmlMeta\Dto\Meta;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
	public static string $xpath = '//html|//html/head/link|//html/head/meta|//html/head/title';

	public readonly Distributor $distributor;

	private string $html;

	private Meta $meta;

	private bool $dontUseDefaultDistributersConfigurationFlag = false;

	public function __construct()
	{
		$this->meta = new Meta();

		$this->distributor = new Distributor();
		$this->distributor->setMeta($this->meta);
	}

	public static function init(?string $html = null): self
	{
		$crawler = new self();
		$crawler->html = $html;

		return $crawler;
	}

	public function dontUseDefaultDistributersConfiguration()
	{
		$this->dontUseDefaultDistributersConfigurationFlag = true;
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

		if (!$this->dontUseDefaultDistributersConfigurationFlag) {
			$this->distributor->useDefaultDistributersConfiguration();
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
