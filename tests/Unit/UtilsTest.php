<?php

namespace Tests\Unit;

use Osmuhin\HtmlMeta\Utils;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Traits\SetupContainer;

use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;

class UtilsTest extends TestCase
{
	use SetupContainer;

	public static function pathsProvider(): array
	{
		return [
			['/path/to/file.ico', 'ico'],
			['path/to/file.with.dots.png', 'png'],
			['/anotherfilename.nonexistentext', 'nonexistentext'],
			['without-slashes.pdf', 'pdf'],
			['/a.sd.//filewithoutextension', null],
			['filewithoutextension', null]
		];
	}

	public static function urlsForSplittingProvider(): array
	{
		return [
			['https://example1.com/', ['https://example1.com', '']],
			['example2.com', ['example2.com', '']],
			['example3.com///some-path', ['example3.com', 'some-path']],
			['http://example4.com/some-path-1/some-path-2//', ['http://example4.com', 'some-path-1/some-path-2']],
		];
	}

	public function test_guess_mime_type_by_file_extension(): void
	{
		assertSame(
			'image/jpeg',
			Utils::guessMimeType('jpeg')
		);

		assertNull(Utils::guessMimeType('nonexistentext'));
	}

	#[DataProvider('pathsProvider')]
	public function test_can_get_file_extension(string $path, ?string $expected): void
	{
		assertSame(
			$expected,
			Utils::guessExtension($path)
		);
	}

	#[DataProvider('urlsForSplittingProvider')]
	public function test_split_url(string $url, array $expected): void
	{
		assertSame(
			$expected,
			Utils::splitUrl($url)
		);
	}

	public function test_url_processing(): void
	{
		$this->config->processUrlsWith('https://x.com/some-path');

		assertSame('https://x.com/some-path/favicon.ico', Utils::processUrl('favicon.ico'));
		assertSame('https://x.com/favicon.ico', Utils::processUrl('/favicon.ico'));
		assertSame('https://apple.com/favicon.ico', Utils::processUrl('https://apple.com/favicon.ico'));
	}
}
