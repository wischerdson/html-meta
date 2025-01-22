<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\DataMappers\MetaDataMapper;

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

	public function canHandle(): bool
	{
		return $this->el->name === 'meta' && $this->el->attributes;
	}

	public function handle(): void
	{
		if ($charset = $this->elAttr('charset')) {
			$this->meta->charset = $charset;

			return;
		}

		if ($name = $this->elAttr('name')) {
			$this->handleNamedMeta($name);

			return;
		}

		if ($property = $this->elAttr('property')) {
			$this->meta->unrecognizedMeta[$property] = $this->elAttr('content', lowercase: false);
		}
	}

	protected function handleNamedMeta(string $name): void
	{
		if (!$content = $this->elAttr('content', lowercase: false)) {
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
				if ($media = $this->elAttr('media', lowercase: false)) {
					$this->meta->themeColor[$media] = $content;
				} else {
					$this->meta->themeColor[] = $content;
				}

				return;
		}

		$this->meta->unrecognizedMeta[$name] = $content;
	}
}
