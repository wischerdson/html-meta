<?php

namespace Osmuhin\HtmlMeta;

use DOMNode;

class Element
{
	public readonly string $name;

	public array $attributes = [];

	public ?string $innerText = null;

	public function __construct(DOMNode $node)
	{
		$this->name = $node->nodeName;

		foreach ($node->attributes as $attr) {
			$this->attributes[$attr->nodeName] = $attr->nodeValue;
		}

		if (
			$node->childNodes->count() === 1 &&
			$node->firstChild->nodeType === XML_TEXT_NODE
		) {
			$this->innerText = $node->textContent;
		}
	}
}
