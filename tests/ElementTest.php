<?php

namespace Tests;

use DOMDocument;
use Osmuhin\HtmlMeta\Element;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;

class ElementTest extends TestCase
{
	public function test(): void
	{
		$dom = new DOMDocument();
		$dom->loadXML('<dIv class="container" data-test="foo"><p>Some paragraph</p>Some inner text<b>Fake bold text</b>123</dIv>');

		$element = new Element($dom->documentElement);

		assertSame('div', $element->name);
		assertSame('Some inner text123', $element->innerText);
		assertSame(['class' => 'container', 'data-test' => 'foo'], $element->attributes);
	}
}
