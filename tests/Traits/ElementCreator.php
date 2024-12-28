<?php

namespace Tests\Traits;

use Osmuhin\HtmlMeta\Element;

trait ElementCreator
{
	protected static function makeMetaWithProperty(?string $property, ?string $content, array $attrs = []): Element
	{
		if ($property !== null) {
			$attrs['property'] = $property;
		}

		if ($content !== null) {
			$attrs['content'] = $content;
		}

		return self::makeMetaElement($attrs);
	}

	protected static function makeNamedMetaElement(?string $name, ?string $content, array $attrs = []): Element
	{
		if ($name !== null) {
			$attrs['name'] = $name;
		}

		if ($content !== null) {
			$attrs['content'] = $content;
		}

		return self::makeMetaElement($attrs);
	}

	protected static function makeMetaElement(array $attributes): Element
	{
		return self::makeElement('meta', $attributes);
	}

	protected static function makeElement(string $name, array $attributes = [], ?string $innerText = null): Element
	{
		/** @var \Osmuhin\HtmlMeta\Element $element */
		$element = self::createStub(Element::class);
		$element->name = $name;
		$element->attributes = $attributes;
		$element->innerText = $innerText;

		return $element;
	}
}
