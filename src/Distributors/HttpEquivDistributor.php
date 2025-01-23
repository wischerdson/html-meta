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
		if (!$name = $this->elAttr('http-equiv')) {
			return false;
		}

		if (!$content = $this->elAttr('content', lowercase: false)) {
			return false;
		}

		$this->name = $name;
		$this->content = $content;

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
