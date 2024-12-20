<?php

namespace Tests\Distributors;

use Osmuhin\HtmlMeta\Distributors\FaviconDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Traits\ElementCreator;

use function PHPUnit\Framework\assertSame;

final class FaviconDistributorTest extends TestCase
{
	use ElementCreator;

	private Meta $meta;

	private FaviconDistributor $distributor;

	protected function setUp(): void
	{
		$this->meta = new Meta();
		$this->distributor = new FaviconDistributor();
		$this->distributor->setMeta($this->meta);
	}

	public static function metaAttributesProvider(): array
	{
		return [
			[[], false],
			[['rel' => '', 'href' => ''], false],
			[['rel' => '12312', 'href' => "  \n "], false],
			[['rel' => '   ', 'href' => '123123'], false],
			[['rel' => '  icon  ', 'href' => " \tfavicon.ico"], true]
		];
	}

	#[DataProvider('metaAttributesProvider')]
	public function test_can_handle_method(array $attributes, bool $expected)
	{
		assertSame($expected, $this->distributor->canHandle(
			self::makeElement('meta', $attributes)
		));
	}

	public function test_handle_method()
	{
		$this->distributor->handle(
			self::makeElement('meta', [])
		);
	}
}
