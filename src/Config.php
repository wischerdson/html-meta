<?php

namespace Osmuhin\HtmlMeta;

class Config
{
	private array $baseUrl = ['', ''];

	private bool $useDefaultDistributorsConfigurationFlag = true;

	private bool $shouldProcessUrlsFlag = true;

	private bool $useTypeConversionFlag = true;

	public function dontProcessUrls($doNot = true): self
	{
		$this->shouldProcessUrlsFlag = !$doNot;

		return $this;
	}

	public function dontUseTypeConversions($doNot = true): self
	{
		$this->useTypeConversionFlag = !$doNot;

		return $this;
	}

	public function processUrlsWith(string $baseUrl): self
	{
		$this->shouldProcessUrlsFlag = true;
		$this->setBaseUrl($baseUrl);

		return $this;
	}

	public function dontUseDefaultDistributorsConfiguration($doNot = true): self
	{
		$this->useDefaultDistributorsConfigurationFlag = !$doNot;

		return $this;
	}

	public function shouldUseTypeConversion(): bool
	{
		return $this->useTypeConversionFlag;
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
		$this->baseUrl = Utils::splitUrl($baseUrl);

		return $this;
	}

	public function getBaseUrl(): array
	{
		return $this->baseUrl;
	}
}
