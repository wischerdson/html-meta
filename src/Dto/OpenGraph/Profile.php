<?php

namespace Osmuhin\HtmlMetaCrawler\Dto\OpenGraph;

use Osmuhin\HtmlMetaCrawler\Contracts\Dto;

class Profile implements Dto
{
	public ?string $firstName = null;

	public ?string $lastName = null;

	public ?string $username = null;

	public ?string $gender = null;

	public function toArray(): array
	{
		return [
			'firstName' => $this->firstName,
			'lastName' => $this->lastName,
			'username' => $this->username,
			'gender' => $this->gender
		];
	}
}
