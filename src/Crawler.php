<?php

namespace Osmuhin\HtmlMeta;

use Composer\InstalledVersions as ComposerInstalledVersions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Osmuhin\HtmlMeta\Contracts\Distributor;
use Osmuhin\HtmlMeta\Distributors\AbstractDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
	public readonly Distributor $distributor;

	public readonly Config $config;

	public string $xpath = '//html|//html/head/link|//html/head/meta|//html/head/title';

	private string $html;

	private string $url;

	private Meta $meta;

	private GuzzleClient $guzzleClient;

	private GuzzleRequest $guzzleRequest;

	public function __construct()
	{
		$this->meta = new Meta();
		$this->config = new Config();

		ServiceLocator::register(
			$this->makeContainer()
		);

		$this->distributor = $this->makeAnonymousDistributor();
	}

	public function __destruct()
	{
		ServiceLocator::destructContainer();
	}

	public static function init(?string $html = null, ?string $url = null, ?GuzzleRequest $request = null): self
	{
		$crawler = new self();

		$html && $crawler->setHtml($html);
		$url && $crawler->setUrl($url);
		$request && $crawler->setRequest($request);

		return $crawler;
	}

	public function setHtml(string $html): self
	{
		$this->html = $html;

		return $this;
	}

	public function setUrl(string $url): self
	{
		$this->config->processUrlsWith($this->url = $url);

		return $this;
	}

	public function setRequest(GuzzleRequest $request): self
	{
		$this->guzzleRequest = $request;

		$this->setUrl((string) $request->getUri());

		return $this;
	}

	public function setGuzzleClient(GuzzleClient $guzzleClient): self
	{
		$this->guzzleClient = $guzzleClient;

		return $this;
	}

	public function run(): Meta
	{
		$html = $this->resolveHtmlString();

		$this->config->shouldUseDefaultDistributorsConfiguration() &&
		$this->useDefaultDistributorsConfiguration();

		$crawler = new DomCrawler($html);

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

	private function makeContainer(): Container
	{
		$container = new Container();
		$container->bind(Meta::class, $this->meta);
		$container->bind(Config::class, $this->config);

		return $container;
	}

	private function makeGuzzleClient(): GuzzleClient
	{
		if (isset($this->guzzleClient)) {
			return $this->guzzleClient;
		}

		$version = ComposerInstalledVersions::getPrettyVersion('osmuhin/html-meta');

		return new GuzzleClient([
			'headers' => [
				'User-Agent' => "OsmuhinHtmlMetaCrawler/{$version}",
				'Accept' => 'text/html,application/xhtml+xml,application/xml'
			]
		]);
	}

	private function makeGuzzleRequest(): GuzzleRequest
	{
		if (isset($this->guzzleRequest)) {
			return $this->guzzleRequest;
		}

		return new GuzzleRequest('GET', $this->url);
	}

	/**
	 * @throws \RuntimeException
	 */
	private function resolveHtmlString(): string
	{
		if (!isset($this->html) && !isset($this->url) && !isset($this->guzzleRequest)) {
			throw new RuntimeException('An HTML string or a url, or a guzzle request object must be provided for parsing.');
		}

		if (isset($this->html)) {
			return $this->html;
		}

		$response = $this->makeGuzzleClient()->send(
			$this->makeGuzzleRequest()
		);

		return $response->getBody();
	}
}
