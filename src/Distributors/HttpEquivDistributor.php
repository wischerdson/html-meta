<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\DataMappers\HttpEquivDataMapper;

class HttpEquivDistributor extends AbstractDistributor
{
	protected string $name;

	protected string $content;

	protected HttpEquivDataMapper $dataMapper;

	public function __construct()
	{
		parent::__construct();

		$this->dataMapper = new HttpEquivDataMapper();
	}

	public function canHandle(): bool
	{
		if (!$this->name = $this->elAttr('http-equiv')) {
			return false;
		}

		if (!$this->content = $this->elAttr('http-equiv', lowercase: false)) {
			return false;
		}

		return true;
	}

	public function handle(): void
	{
		if ($this->dataMapper->assign($this->name, $this->content)) {
			return;
		}

		$this->meta->httpEquiv->other[$this->name] = $this->content;
	}
}
