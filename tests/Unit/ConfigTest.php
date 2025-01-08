<?php

namespace Tests\Unit;

use Osmuhin\HtmlMeta\Config;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class ConfigTest extends TestCase
{
	public function test_process_urls(): void
	{
		$config = new Config();

		assertTrue($config->shouldProcessUrls());

		$config->dontProcessUrls();
		assertFalse($config->shouldProcessUrls());

		$config->dontProcessUrls(false);
		assertTrue($config->shouldProcessUrls());
	}

	public function test_type_conversions(): void
	{
		$config = new Config();

		assertTrue($config->shouldUseTypeConversion());

		$config->dontUseTypeConversions();
		assertFalse($config->shouldUseTypeConversion());

		$config->dontUseTypeConversions(false);
		assertTrue($config->shouldUseTypeConversion());
	}

	public function test_default_distributors_configuration(): void
	{
		$config = new Config();

		assertTrue($config->shouldUseDefaultDistributorsConfiguration());

		$config->dontUseDefaultDistributorsConfiguration();
		assertFalse($config->shouldUseDefaultDistributorsConfiguration());

		$config->dontUseDefaultDistributorsConfiguration(false);
		assertTrue($config->shouldUseDefaultDistributorsConfiguration());
	}
}
