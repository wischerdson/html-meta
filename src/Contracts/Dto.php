<?php

namespace Osmuhin\HtmlMetaCrawler\Contracts;

interface Dto
{
	public static function getPropertiesMap(): array;

	public function toArray(): array;
}
