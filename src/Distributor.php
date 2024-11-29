<?php

namespace Osmuhin\HtmlMetaCrawler;

use Osmuhin\HtmlMetaCrawler\Contracts\Distributor as DistributorContract;
use Osmuhin\HtmlMetaCrawler\Contracts\Dto;
use Osmuhin\HtmlMetaCrawler\Dto\Meta;

class Distributor implements DistributorContract
{
	private DistributorOpenGraph $ogDistributor;

	private DistributorHttpEquiv $httpEquivDistributor;

	public function __construct(private Meta $meta)
	{
		$this->ogDistributor = new DistributorOpenGraph($this->meta->openGraph);
		$this->httpEquivDistributor = new DistributorHttpEquiv($this->meta->httpEquiv);
	}

	public static function assignAccordingToTheMap(Dto $object, string $name, string $content)
	{
		$map = $object::getPropertiesMap();

		if (isset($map[$name])) {
			$object->{$map[$name]} ??= $content;

			return true;
		}

		return false;
	}

	public function setHtml(Element $html): void
	{
		$this->meta->lang = @$html->attributes['lang'];
		$this->meta->dir = @$html->attributes['dir'];
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
			$name = $meta->attributes['http-equiv'];

			if (!$name = mb_strtolower(trim($name), 'UTF-8')) {
				return;
			}

			if (!$content = @$meta->attributes['content']) {
				return;
			}

			$this->httpEquivDistributor->set($name, $content);

			return;
		}
	}

	public function setLink(Element $link): void
	{

	}

	protected function handleNamedMeta(string $name, Element $meta): void
	{
		if (!$name = mb_strtolower(trim($name), 'UTF-8')) {
			return;
		}

		if (!$content = @$meta->attributes['content']) {
			return;
		}

		if (self::assignAccordingToTheMap($this->meta, $name, $content)) {
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

	protected function handleMetaWithProperty(string $property, Element $meta): void
	{
		$property = mb_strtolower(trim($property), 'UTF-8');

		if (!$content = @$meta->attributes['content']) {
			return;
		}

		if ($this->ogDistributor->set($property, $content)) {
			return;
		}

		if (preg_match("/^twitter\:(.*)/i", $property, $matches)) {
			$this->meta->twitter[$matches[1]] = @$meta->attributes['content'];
			unset($matches);

			return;
		}
	}
}
