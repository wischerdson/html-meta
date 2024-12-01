<?php

namespace Osmuhin\HtmlMeta\Contracts;

use Osmuhin\HtmlMeta\Dto\Meta;
use Osmuhin\HtmlMeta\Element;

interface Distributor
{
	public static function init(): self;

	public function setMeta(Meta $meta): self;

	/**
	 * @param \Osmuhin\HtmlMeta\Contracts\Distributor[] $args
	 */
	public function useSubDistributors(...$args): self;

	/**
	 * Checks whether the distributor can handle the current element.
	 * If returns true, then all sub-distributors are polled, and then the handle method is called.
	 */
	public function canHandle(Element $el): bool;

	public function handle(Element $el): void;
}
