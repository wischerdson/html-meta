<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\DataMappers\MetaDataMapper;
use Osmuhin\HtmlMeta\Element;

class MetaDistributor extends AbstractDistributor
{
	protected MetaDataMapper $dataMapper;

	/**
	 * For testing purposes only
	 *
	 * @see \tests\Unit\Distributors\MetaDistributorTest
	 */
	private bool $testEmptyContent = false;

	/**
	 * For testing purposes only
	 *
	 * @see \tests\Unit\Distributors\MetaDistributorTest
	 */
	private bool $testAssignment = false;

	public function __construct()
	{
		parent::__construct();

		$this->dataMapper = new MetaDataMapper();
	}

	public function canHandle(Element $el): bool
	{
		return $el->name === 'meta' && $el->attributes;
	}

	public function handle(Element $el): void
	{
		if ($charset = @$el->attributes['charset']) {
			$this->meta->charset = $charset;

			return;
		}

		if ($name = @$el->attributes['name']) {
			$name = mb_strtolower(trim($name), 'UTF-8');
			$name && $this->handleNamedMeta($name, $el);
		}
	}

	protected function handleNamedMeta(string $name, Element $meta): void
	{
		$content = @$meta->attributes['content'];

		if (!$content || !$content = trim($content)) {
			$this->testEmptyContent = true;

			return;
		}

		if ($this->dataMapper->assign($name, $content)) {
			$this->testAssignment = true;

			return;
		}

		switch ($name) {
			case 'title':
				$this->meta->title ??= $content;

				return;
			case 'theme-color':
				if ($media = @$meta->attributes['media']) {
					$this->meta->themeColor[$media] = $content;
				} else {
					$this->meta->themeColor[] = $content;
				}

				return;
		}
	}
}
