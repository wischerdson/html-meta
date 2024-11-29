<?php

use Osmuhin\HtmlMetaCrawler\Distributor;
use Osmuhin\HtmlMetaCrawler\Dto\Meta;
use Osmuhin\HtmlMetaCrawler\Dto\OpenGraph;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Tests\Traits\ElementCreator;

class DistributorOpenGraphTest extends TestCase
{
	use ElementCreator;

	private Meta $meta;

	private Distributor $distibutor;

	public function setUp(): void
	{
		$this->meta = new Meta();
		$this->distibutor = new Distributor($this->meta);
	}

	#[Test]
	#[TestDox('')]
	public function test_1()
	{
		$simpleProperties = OpenGraph::getPropertiesMap();

		foreach ($simpleProperties as $propertyInTag => $propertyInObject) {
			$content1 = "Some content for the property {$propertyInTag}";
			$content2 = "Duplicate property {$propertyInTag} with another content";
			$element1 = $this->makeMetaWithProperty($propertyInTag, $content1);
			$element2 = $this->makeMetaWithProperty($propertyInTag, $content2);

			$this->distibutor->setMeta($element1);
			$this->distibutor->setMeta($element2);

			$this->assertEquals($content1, $this->meta->openGraph->$propertyInObject);
		}
	}

	#[Test]
	#[TestDox('')]
	public function test_2()
	{
		$element1 = $this->makeMetaWithProperty('video:duration', '123123');
		$this->distibutor->setMeta($element1);
	}
}
