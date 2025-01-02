<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\DataMappers\TwitterDataMapper;
use Osmuhin\HtmlMeta\Element;

class TwitterDistributor extends AbstractDistributor
{
	protected string $name;

	protected string $content;

	protected TwitterDataMapper $dataMapper;

	public function __construct()
	{
		parent::__construct();

		$this->dataMapper = new TwitterDataMapper();
	}

	public function canHandle(Element $el): bool
	{
		$name = @$el->attributes['name'] ?: @$el->attributes['property'];

		if (!$name || !str_starts_with($name, 'twitter:')) {
			return false;
		}

		if (
			(!$content = @$el->attributes['content']) ||
			(!$content = trim($content))
		) {
			return false;
		}

		$this->name = trim($name);
		$this->content = $content;

		return true;
	}

	public function handle(Element $el): void
	{
		if ($this->dataMapper->assign($this->name, $this->content)) {
			return;
		}

		$this->meta->twitter->other[$this->name] = $this->content;
	}
}
