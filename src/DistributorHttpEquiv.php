<?php

namespace Osmuhin\HtmlMeta;

use Osmuhin\HtmlMeta\Contracts\Distributor;
use Osmuhin\HtmlMeta\Dto\HttpEquiv;

class DistributorHttpEquiv implements Distributor
{
	public function __construct(private HttpEquiv $httpEquiv)
	{

	}

	/**
	 * @param string $name The "http-equiv" attribute of the meta tag
	 *
	 * Example: <meta http-equiv="refresh" content="3;url=https://www.mozilla.org"\> -> $name == 'refresh'
	 *
	 * @param string $content The "content" attribute of the meta tag
	 *
	 * Example: <meta http-equiv="refresh" content="3;url=https://www.mozilla.org"\> -> $content == '3;url=https://www.mozilla.org'
	 */
	public function set(string $name, string $content): void
	{
		if (Crawler::$distributorClass::assignAccordingToTheMap($this->httpEquiv, $name, $content)) {
			return;
		}

		$this->httpEquiv->other[$name] = $content;
	}
}
