<?php

use Osmuhin\HtmlMetaCrawler\Element;
use Osmuhin\HtmlMetaCrawler\ElementsCollection;
use Osmuhin\HtmlMetaCrawler\Meta;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
	#[Test]
	#[TestDox('Проверка свойства "description"')]
	public function test_0(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->addMeta(
				$this->makeNamedMetaElement('description', 'Some description')
			)
		);

		$this->assertSame('Some description', $meta->description);
	}

	#[Test]
	#[TestDox('Проверка свойства "charset"')]
	public function test_1(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->addMeta(
				$this->makeMetaElement(['charset' => 'UTF-32'])
			)
		);

		$this->assertSame('UTF-32', $meta->charset);
	}

	#[Test]
	#[TestDox('Проверка свойства "colorScheme"')]
	public function test_2(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->addMeta(
				$this->makeNamedMetaElement('color-scheme', 'light dark')
			)
		);

		$this->assertSame('light dark', $meta->colorScheme);
	}

	#[Test]
	#[TestDox('Проверка свойства "themeColor"')]
	public function test_3(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->addMeta(
				$this->makeNamedMetaElement('theme-color', '#eb0c0c')
			)
		);

		$this->assertArrayHasKey(0, $meta->themeColor);
		$this->assertContains('#eb0c0c', $meta->themeColor);

		/* ====================== */

		$element1 = $this->makeNamedMetaElement('theme-color', 'cyan', ['media' => '(prefers-color-scheme: light)']);
		$element2 = $this->makeNamedMetaElement('theme-color', 'black', ['media' => '(prefers-color-scheme: dark)']);

		$collection = ElementsCollection::init()->addMeta($element1)->addMeta($element2);

		$meta = new Meta($collection);

		$this->assertArrayHasKey('(prefers-color-scheme: light)', $meta->themeColor);
		$this->assertArrayHasKey('(prefers-color-scheme: dark)', $meta->themeColor);
		$this->assertSame('cyan', $meta->themeColor['(prefers-color-scheme: light)']);
		$this->assertSame('black', $meta->themeColor['(prefers-color-scheme: dark)']);
	}

	#[Test]
	#[TestDox('Проверка свойства "applicationName"')]
	public function test_4(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->addMeta(
				$this->makeNamedMetaElement('application-name', 'Osmuhin\'s crawler')
			)
		);

		$this->assertSame('Osmuhin\'s crawler', $meta->applicationName);
	}

	#[Test]
	#[TestDox('Проверка свойства "author"')]
	public function test_5(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->addMeta(
				$this->makeNamedMetaElement('author', 'Osmuhin')
			)
		);

		$this->assertSame('Osmuhin', $meta->author);
	}

	#[Test]
	#[TestDox('Проверка свойства "generator"')]
	public function test_6(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->addMeta(
				$this->makeNamedMetaElement('generator', 'Brain')
			)
		);

		$this->assertSame('Brain', $meta->generator);
	}

	#[Test]
	#[TestDox('Проверка свойства "lang"')]
	public function test_7(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->setHtml(
				$this->makeElement('<html lang="ru_RU"></html>')
			)
		);

		$this->assertSame('ru_RU', $meta->lang);
	}

	#[Test]
	#[TestDox('Проверка свойства "referrer"')]
	public function test_8(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->addMeta($this->makeNamedMetaElement('referrer', 'unsafe-URL'))
		);

		$this->assertSame('unsafe-URL', $meta->referrer);
	}

	#[Test]
	#[TestDox('Проверка свойства "title"')]
	public function test_9(): void
	{
		/* Если задан тег <title>Title case 1</title> */
		$meta = new Meta(
			ElementsCollection::init()->setTitle(
				$this->makeElement('<title>Title case 1</title>')
			)
		);

		$this->assertSame('Title case 1', $meta->title);

		/* Если задан мета-тег <meta name="title" content="Title case 2"> */
		$meta = new Meta(
			ElementsCollection::init()->addMeta(
				$this->makeNamedMetaElement('title', 'Title case 2')
			)
		);

		$this->assertSame('Title case 2', $meta->title);

		/* Если задано и то, и другое, то приоритет тегу <title> */
		$meta = new Meta(
			ElementsCollection::init()->addMeta(
				$this->makeNamedMetaElement('title', 'meta-tag')
			)->setTitle(
				$this->makeElement('<title>title-tag</title>')
			)
		);

		$this->assertSame('title-tag', $meta->title);
	}

	#[Test]
	#[TestDox('Проверка свойства "viewport"')]
	public function test_10(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->addMeta(
				$this->makeNamedMetaElement('viewport', 'width=device-width,initial-scale=1')
			)
		);

		$this->assertSame('width=device-width,initial-scale=1', $meta->viewport);
	}

	#[Test]
	#[TestDox('Проверка свойства "keywords"')]
	public function test_11(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->addMeta(
				$this->makeNamedMetaElement('keywords', 'composer, library, html-meta')
			)
		);

		$this->assertSame('composer, library, html-meta', $meta->keywords);
	}

	#[Test]
	#[TestDox('Проверка свойства "htmlAttributes"')]
	public function test_12(): void
	{
		$meta = new Meta(
			ElementsCollection::init()->setHtml(
				$this->makeElement('<html some-attribute-1="foo" some-attribute-2="bar" lang="ru_RU"></html>')
			)
		);

		$this->assertArrayHasKey('some-attribute-1', $meta->htmlAttributes);
		$this->assertArrayHasKey('some-attribute-2', $meta->htmlAttributes);
		$this->assertArrayHasKey('lang', $meta->htmlAttributes);
		$this->assertSame('foo', $meta->htmlAttributes['some-attribute-1']);
		$this->assertSame('bar', $meta->htmlAttributes['some-attribute-2']);
		$this->assertSame('ru_RU', $meta->htmlAttributes['lang']);
	}

	#[Test]
	#[TestDox('Проверка свойства "twitter"')]
	public function test_13(): void
	{
		$collection = new ElementsCollection();
		$collection->addMeta($this->makeNamedMetaElement('twitter:card', 'summary_large_image'));
		$collection->addMeta($this->makeMetaElement(['property' => 'twitter:url', 'content' => 'https://apple.com']));

		$meta = new Meta($collection);

		$this->assertArrayHasKey('card', $meta->twitter);
		$this->assertArrayHasKey('url', $meta->twitter);

		$this->assertSame('summary_large_image', $meta->twitter['card']);
		$this->assertSame('https://apple.com', $meta->twitter['url']);
	}

	/* ============================================================ */

	private function makeNamedMetaElement(string $name, string $content, array $attrs = [])
	{
		return $this->makeMetaElement(['name' => $name, 'content' => $content] + $attrs);
	}

	private function makeMetaElement(array $attributes)
	{
		$glued = [];

		foreach ($attributes as $key => $value) {
			$glued[] = "{$key}=\"{$value}\"";
		}

		$html = implode(' ', $glued);

		return $this->makeElement("<meta {$html} />");
	}

	private function makeElement(string $xml): Element
	{
		$dom = new DOMDocument();
		$dom->loadXML($xml);

		return new Element($dom->documentElement);
	}
}
