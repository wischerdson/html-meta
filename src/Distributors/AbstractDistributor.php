<?php

namespace Osmuhin\HtmlMeta\Distributors;

use InvalidArgumentException;
use Osmuhin\HtmlMeta\Config;
use Osmuhin\HtmlMeta\Container;
use Osmuhin\HtmlMeta\Contracts\Distributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use Osmuhin\HtmlMeta\Element;
use Osmuhin\HtmlMeta\ServiceLocator;

abstract class AbstractDistributor implements Distributor
{
	protected Container $container;

	protected Config $config;

	protected Meta $meta;

	/** @var \Osmuhin\HtmlMeta\Distributors\AbstractDistributor[] */
	private array $subDistributors = [];

	public function __construct()
	{
		$this->container = ServiceLocator::container();
		$this->meta = $this->container->get(Meta::class);
		$this->config = $this->container->get(Config::class);
	}

	public static function init(): self
	{
		return new static();
	}

	public function useSubDistributors(...$args): self
	{
		$distributors = $args;

		foreach ($distributors as $distributor) {
			$this->setSubDistributor($distributor);
		}

		return $this;
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	public function setSubDistributor(Distributor|string $distributor, ?string $key = null): self
	{
		$distributor = is_string($distributor) ? new $distributor() : $distributor;

		if (!($distributor instanceof Distributor)) {
			$class = $distributor::class;
			throw new InvalidArgumentException("{$class} must implements \Osmuhin\HtmlMeta\Contracts\Distributor interface");
		}

		$key ??= $distributor::class;

		$this->subDistributors[$key] = $distributor;

		return $this;
	}

	public function getSubDistributor(string $key): Distributor|null
	{
		return @$this->subDistributors[$key];
	}

	protected function pollSubDistributors(Element $el): bool
	{
		foreach ($this->subDistributors as $subDistributor) {
			if ($subDistributor->canHandle($el)) {
				$subDistributor->pollSubDistributors($el) || $subDistributor->handle($el);

				return true;
			}
		}

		return false;
	}
}
