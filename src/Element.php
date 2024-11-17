<?php

namespace Osmuhin\HtmlMetaCrawler;

use DOMNode;

class Element
{
	public readonly string $type;

	public readonly DOMNode $node;

	public array $attributes = [];

	public ?string $innerText = null;

	public function __construct(DOMNode $node)
	{
		$this->node = $node;
		$this->type = $node->nodeName;
		$this->setInnerText();
		$this->setAttributes();
	}

	private function setAttributes()
	{
		foreach ($this->node->attributes as $attr) {
			$this->attributes[$attr->nodeName] = $attr->nodeValue;
		}
	}

	private function setInnerText()
	{
		if (
			$this->node->childNodes->count() === 1 &&
			$this->node->firstChild->nodeType === XML_TEXT_NODE
		) {
			$this->innerText = $this->node->textContent;
		}
	}
}
