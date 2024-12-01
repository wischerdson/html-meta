<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\Contracts\Distributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use Osmuhin\HtmlMeta\Element;

abstract class AbstractDistributor implements Distributor
{
	protected Meta $meta;

	/** @var \Osmuhin\HtmlMeta\Distributors\AbstractDistributor[] */
	private array $subDistributors = [];

	public static function init(): self
	{
		return new static();
	}

	public function setMeta(Meta $meta): self
	{
		$this->meta = $meta;

		foreach ($this->subDistributors as $subDistributor) {
			$subDistributor->setMeta($meta);
		}

		return $this;
	}

	public function useSubDistributors(...$args): self
	{
		$distributors = $args;

		foreach ($distributors as $distributor) {
			$this->subDistributors[] = $distributor;
		}

		return $this;
	}

	protected static function assignAccordingToTheMap(array $map, object $object, string $name, string $content): bool
	{
		if (isset($map[$name])) {
			$object->{$map[$name]} ??= $content;

			return true;
		}

		return false;
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

	protected function eachSubDistributors(): iterable
	{
		foreach ($this->subDistributors as $subDistributor) {
			yield $subDistributor;
		}
	}
}
