<?php

namespace Osmuhin\HtmlMetaCrawler;

use Osmuhin\HtmlMetaCrawler\Dto\Meta;

class Distributor
{
	private Meta $meta;

	public function __construct()
	{
		$this->meta = new Crawler::$metaClass();
	}

	public function setHtml(Element $html): void
	{
		$this->meta->lang = @$html->attributes['lang'];
		$this->meta->htmlAttributes = $html->attributes;
	}

	public function setTitle(Element $title): void
	{
		$this->meta->title = $title->innerText;
	}

	public function setMeta(Element $meta): void
	{
		if ($this->meta->charset = @$meta->attributes['charset']) {
			return;
		}

		if (isset($meta->attributes['name'])) {
			$this->handleNamedMeta($meta->attributes['name'], $meta);

			return;
		}

		if (isset($meta->attributes['property'])) {
			$this->handleMetaWithProperty($meta->attributes['property'], $meta);

			return;
		}

		if (isset($meta->attributes['http-equiv'])) {
			$this->handleHttpEquivMeta($meta->attributes['http-equiv'], $meta);

			return;
		}
	}

	public function setLink(Element $link): void
	{

	}

	public function getMeta(): Meta
	{
		return $this->meta;
	}

	protected function handleNamedMeta(string $name, Element $meta): void
	{
		if (!$name = mb_strtolower(trim($name), 'UTF-8')) {
			return;
		}

		if (!$content = @$meta->attributes['content']) {
			return;
		}

		$map = $this->meta->getPropertiesMap();

		if (isset($map[$name])) {
			$this->meta->{$map[$name]} = $content;

			return;
		}

		switch ($name) {
			case 'title':
				$this->meta->title ??= $content;
				return;
			case 'theme-color':
				if ($media = @$meta->attributes['media']) {
					$this->meta->themeColor[$media] = $content;
				} else {
					$this->meta->themeColor[] = $content;
				}

				return;
		}

		if (preg_match("/^twitter\:(.*)/i", $name, $matches)) {
			$this->meta->twitter[$matches[1]] = $content;

			return;
		}
	}

	protected function handleMetaWithProperty(string $property, Element $meta): bool
	{
		if (preg_match("/^og\:/i", $property)) {
			dump('opengraph');

			return true;
		}

		if (preg_match("/^twitter\:(.*)/i", $property, $matches)) {
			return $this->meta->twitter[$matches[1]] = @$meta->attributes['content'];
		}

		return false;
	}

	protected function handleHttpEquivMeta(string $name, Element $meta): void
	{
		if (!$name = mb_strtolower(trim($name), 'UTF-8')) {
			return;
		}

		if (!$content = @$meta->attributes['content']) {
			return;
		}

		$map = $this->meta->httpEquiv->getPropertiesMap();

		if (isset($map[$name])) {
			$this->meta->httpEquiv->{$map[$name]} = $content;

			return;
		}

		$this->meta->httpEquiv->other[$name] = $content;
	}
}
