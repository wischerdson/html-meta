<?php

namespace Osmuhin\HtmlMeta;

class Config
{
	private string $baseUrl = '/';

	private bool $useDefaultDistributorsConfigurationFlag = true;

	private bool $shouldProcessUrlsFlag = true;

	public function disableUrlProcessing(): self
	{
		$this->shouldProcessUrlsFlag = false;

		return $this;
	}

	public function processUrlsWith(string $baseUrl): self
	{
		$this->shouldProcessUrlsFlag = true;
		$this->baseUrl = $baseUrl;

		return $this;
	}

	public function dontUseDefaultDistributorsConfiguration(): self
	{
		$this->useDefaultDistributorsConfigurationFlag = false;

		return $this;
	}

	public function shouldUseDefaultDistributorsConfiguration(): bool
	{
		return $this->useDefaultDistributorsConfigurationFlag;
	}

	public function shouldProcessUrls(): bool
	{
		return $this->shouldProcessUrlsFlag;
	}

	public function setBaseUrl(string $baseUrl): self
	{
		$this->baseUrl = $baseUrl;

		return $this;
	}

	public function getBaseUrl(): string
	{
		return $this->baseUrl;
	}
}
