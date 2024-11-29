<?php

namespace Osmuhin\HtmlMetaCrawler;

use Osmuhin\HtmlMetaCrawler\Contracts\Distributor;
use Osmuhin\HtmlMetaCrawler\Dto\OpenGraph;
use Osmuhin\HtmlMetaCrawler\Dto\OpenGraph\Image;

class DistributorOpenGraph implements Distributor
{
	private array $methodsMap;

	public function __construct(private OpenGraph $og)
	{
		$this->methodsMap = self::getMethodsMap();
	}

	/**
	 * @param string $property The "property" attribute of the opengraph meta tag
	 *
	 * Example: <meta property="og:type" content="website"\> -> $property == 'og:type'
	 *
	 * @param string $content The "content" attribute of the opengraph meta tag
	 *
	 * Example: <meta property="og:type" content="website"\> -> $content == 'website'
	 *
	 * @return bool If the data recognized as an opengraph property, then return true, otherwise false
	 */
	public function set(string $property, string $content): bool
	{
		$namespace = explode(':', $property)[0];

		return $this->call($namespace, $property, $content);
	}

	protected static function getMethodsMap()
	{
		return [
			'og' => 'setOg',
			'image' => 'setImage',
			'music' => 'setMusic',
			'video' => 'setVideo',
			'article' => 'setArticle',
			'book' => 'setBook',
			'profile' => 'setProfile',
		];
	}

	protected function call(string $namespace, string $property, string $content)
	{
		if ($method = @$this->methodsMap[$namespace]) {
			return $this->{$method}($property, $content);
		}

		return false;
	}

	protected function setOg(string $property, string $content): bool
	{
		if (Crawler::$distributorClass::assignAccordingToTheMap($this->og, $property, $content)) {
			return true;
		}

		if ($property === 'og:locale:alternate') {
			$this->og->alternateLocales[] = $content;

			return true;
		}

		if ($namespace = @explode(':', $property)[1]) {
			return $this->call($namespace, $property, $content);
		}

		return false;
	}

	protected function setImage(string $property, string $content)
	{
		if (str_ends_with($property, 'image') || str_ends_with($property, 'image:url')) {
			$image = new Image();
		} else {
			$image = array_pop($this->og->images);
		}

		Crawler::$distributorClass::assignAccordingToTheMap($image, $property, $content);

		$this->og->images[] = $image;
	}

	protected function setMusic(string $property, string $content)
	{

	}
}
