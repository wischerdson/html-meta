<?php

namespace Tests\Traits;

use DOMDocument;
use Osmuhin\HtmlMetaCrawler\Element;

trait ElementCreator
{
	protected function makeMetaWithProperty(string $property, string $content, array $attrs = [])
	{
		return $this->makeMetaElement(['property' => $property, 'content' => $content] + $attrs);
	}

	protected function makeNamedMetaElement(string $name, string $content, array $attrs = [])
	{
		return $this->makeMetaElement(['name' => $name, 'content' => $content] + $attrs);
	}

	protected function makeMetaElement(array $attributes)
	{
		$glued = [];

		foreach ($attributes as $key => $value) {
			$glued[] = "{$key}=\"{$value}\"";
		}

		$html = implode(' ', $glued);

		return $this->makeElement("<meta {$html} />");
	}

	protected function makeElement(string $xml): Element
	{
		$dom = new DOMDocument();
		$dom->loadXML($xml);

		return new Element($dom->documentElement);
	}
}
