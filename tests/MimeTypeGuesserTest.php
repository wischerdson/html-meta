<?php

use Osmuhin\HtmlMeta\MimeTypeGuesser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;

class MimeTypeGuesserTest extends TestCase
{
	public static function pathsProvider(): array
	{
		return [
			['/path/to/file.ico', 'ico'],
			['path/to/file.with.dots.png', 'png'],
			['/anotherfilename.nonexistentext', 'nonexistentext'],
			['without-slashes.pdf', 'pdf'],
			['filewithoutextension', null]
		];
	}

	public function test_guess_mime_type_by_file_extension(): void
	{
		assertSame(
			'image/jpeg',
			MimeTypeGuesser::guessMimeType('jpeg')
		);

		assertNull(MimeTypeGuesser::guessMimeType('nonexistentext'));
	}

	#[DataProvider('pathsProvider')]
	public function test_can_get_file_extension(string $path, ?string $result): void
	{
		assertSame(
			$result,
			MimeTypeGuesser::guessExtension($path)
		);
	}
}
