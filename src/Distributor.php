<?php

namespace Osmuhin\HtmlMetaCrawler;

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

		if (
			($name = @$meta->attributes['name']) &&
			$this->handleNamedMeta($name, $meta)
		) {
			return;
		}

		if (
			($property = @$meta->attributes['property']) &&
			$this->handleMetaWithProperty($property, $meta)
		) {
			return;
		}

		$this->meta->unrecognizedMeta[] = $meta;
	}

	public function setLink(Element $link): void
	{

	}

	public function getMeta(): Meta
	{
		return $this->meta;
	}

	protected function handleNamedMeta(string $name, Element $meta): bool
	{
		$name = mb_strtolower($name, 'UTF-8');
		$content = @$meta->attributes['content'];

		switch ($name) {
			case 'viewport':
				return $this->meta->viewport = $content;
			case 'title':
				return $this->meta->title ??= $content;
			case 'description':
				return $this->meta->description = $content;
			case 'color-scheme':
				return $this->meta->colorScheme = $content;
			case 'author':
				return $this->meta->author = $content;
			case 'keywords':
				return $this->meta->keywords = $content;
			case 'application-name':
				return $this->meta->applicationName = $content;
			case 'generator':
				return $this->meta->generator = $content;
			case 'referrer':
				return $this->meta->referrer = $content;
			case 'theme-color':
				if ($media = @$meta->attributes['media']) {
					$this->meta->themeColor[$media] = $content;
				} else {
					$this->meta->themeColor[] = $content;
				}

				return $this->meta->themeColor;
		}

		if (preg_match("/^twitter\:(.*)/i", $name, $matches)) {
			return $this->meta->twitter[$matches[1]] = $content;
		}

		return false;
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
}
