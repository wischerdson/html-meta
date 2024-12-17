<?php

namespace Osmuhin\HtmlMeta;

use Osmuhin\HtmlMeta\Contracts\Distributor;
use Osmuhin\HtmlMeta\Distributors\AbstractDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
	public readonly Distributor $distributor;

	public string $xpath = '//html|//html/head/link|//html/head/meta|//html/head/title';

	private string $html;

	private Meta $meta;

	private bool $useDefaultDistributorsConfigurationFlag = true;

	public function __construct()
	{
		$this->meta = new Meta();

		$this->distributor = $this->makeAnonymousDistributor();
		$this->distributor->setMeta($this->meta);
	}

	public static function init(?string $html = null): self
	{
		$crawler = new self();
		$crawler->html = $html;

		return $crawler;
	}

	public function dontUseDefaultDistributorsConfiguration()
	{
		$this->useDefaultDistributorsConfigurationFlag = false;
	}

	public function setHtml(string $html): self
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

		$this->useDefaultDistributorsConfigurationFlag &&
		$this->useDefaultDistributorsConfiguration();

		$crawler = new DomCrawler($this->html);

		foreach ($crawler->filterXPath($this->xpath) as $node) {
			$this->distributor->handle(
				new Element($node)
			);
		}

		return $this->meta;
	}

	private function makeAnonymousDistributor()
	{
		return new class extends AbstractDistributor {
			public function canHandle(Element $element): bool
			{
				return true;
			}

			public function handle(Element $element): void
			{
				$this->pollSubDistributors($element);
			}
		};
	}

	private function useDefaultDistributorsConfiguration()
	{
		$this->distributor->useSubDistributors(
			\Osmuhin\HtmlMeta\Distributors\HtmlDistributor::init(),
			\Osmuhin\HtmlMeta\Distributors\TitleDistributor::init(),
			\Osmuhin\HtmlMeta\Distributors\MetaDistributor::init()->useSubDistributors(
				\Osmuhin\HtmlMeta\Distributors\HttpEquivDistributor::init(),
				\Osmuhin\HtmlMeta\Distributors\TwitterDistributor::init(),
				\Osmuhin\HtmlMeta\Distributors\OpenGraphDistributor::init()
			),
			\Osmuhin\HtmlMeta\Distributors\LinkDistributor::init()->useSubDistributors(
				\Osmuhin\HtmlMeta\Distributors\FaviconDistributor::init()
			)
		);
	}
}
