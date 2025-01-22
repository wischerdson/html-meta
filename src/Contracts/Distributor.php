<?php

namespace Osmuhin\HtmlMeta\Contracts;

interface Distributor
{
	public static function init(): self;

	/**
	 * @param \Osmuhin\HtmlMeta\Contracts\Distributor|string ...$args
	 * @param \Osmuhin\HtmlMeta\Contracts\Distributor[]|string[] $args
	 */
	public function useSubDistributors(...$args): self;

	public function setSubDistributor(self|string $distributor, ?string $insteadOf = null): self;

	public function getSubDistributor(string $class): self|null;

	/**
	 * Checks whether the distributor can handle the current element.
	 * If returns true, then all sub-distributors are polled, and then the handle method is called.
	 */
	public function canHandle(): bool;

	public function handle(): void;
}
