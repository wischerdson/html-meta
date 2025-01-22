<?php

namespace Osmuhin\HtmlMeta\Distributors;

use Osmuhin\HtmlMeta\DataMappers\TwitterDataMapper;

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

	public function canHandle(): bool
	{
		$this->name = $this->elAttr('name') ?: $this->elAttr('property');

		if (!$this->name || !str_starts_with($this->name, 'twitter:')) {
			return false;
		}

		if (!$this->content = $this->elAttr('content', lowercase: false)) {
			return false;
		}

		return true;
	}

	public function handle(): void
	{
		if ($this->dataMapper->assign($this->name, $this->content)) {
			return;
		}

		$this->meta->twitter->other[$this->name] = $this->content;
	}
}
