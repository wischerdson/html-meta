<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\DataMappers\HttpEquivDataMapper;
use Osmuhin\HtmlMeta\Element;

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

	public function canHandle(Element $el): bool
	{
		if (!$name = @$el->attributes['http-equiv']) {
			return false;
		}

		if (!$name = mb_strtolower(trim($name), 'UTF-8')) {
			return false;
		}

		$content = @$el->attributes['content'];

		if (!$content || !$content = trim($content)) {
			return false;
		}

		$this->name = $name;
		$this->content = $content;

		return true;
	}

	public function handle(Element $el): void
	{
		if ($this->dataMapper->assign($this->name, $this->content)) {
			return;
		}

		$this->meta->httpEquiv->other[$this->name] = $this->content;
	}
}
