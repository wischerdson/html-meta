<?php

namespace Osmuhin\HtmlMeta\Contracts;

interface Dto
{
	public static function getPropertiesMap(): array;

	public function toArray(): array;
}
