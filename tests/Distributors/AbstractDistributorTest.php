<?php

namespace Tests\Distributors;

use InvalidArgumentException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Osmuhin\HtmlMeta\Distributors\AbstractDistributor;
use Osmuhin\HtmlMeta\Dto\Meta;
use Osmuhin\HtmlMeta\Element;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Tests\Fixtures\Distributors\SubDistributor1;
use Tests\Fixtures\Distributors\SubDistributor2;
use Tests\Fixtures\Distributors\SubDistributor3;
use Tests\Traits\ElementCreator;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertIsObject;
use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

final class AbstractDistributorTest extends TestCase
{
	use ElementCreator;
	use MockeryPHPUnitIntegration;

	private AbstractDistributor $distributor;

	protected function setUp(): void
	{
		$this->distributor = new class extends AbstractDistributor {
			public function canHandle(Element $el): bool
			{
				return true;
			}

			public function handle(Element $el): void
			{

			}
		};
	}

	public function test_init(): void
	{
		$distributor = $this->distributor::init();

		assertIsObject($distributor);
		assertInstanceOf(AbstractDistributor::class, $distributor);
	}

	#[TestDox('Test whether InvalidArgumentException will be thrown in sub distributor dont implements required interface')]
	public function test_whether_exception_will_be_thrown(): void
	{
		$subDistributor = new class {};
		$class = $subDistributor::class;

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("{$class} must implements \Osmuhin\HtmlMeta\Contracts\Distributor interface");

		$this->distributor->setSubDistributor($class);
	}

	public function test_can_set_and_get_sub_distributor(): void
	{
		$subDistributor1 = SubDistributor1::init();
		$subDistributor2 = SubDistributor2::init();

		$this->distributor->setSubDistributor($subDistributor1, 'someKey');

		assertSame($subDistributor1, $this->distributor->getSubDistributor('someKey'));

		assertNull($this->distributor->getSubDistributor($subDistributor1::class));

		$this->distributor->setSubDistributor($subDistributor2);
		assertSame($subDistributor2, $this->distributor->getSubDistributor($subDistributor2::class));
	}

	public function test_can_set_multiple_sub_distributors(): void
	{
		$this->distributor->useSubDistributors(
			$sd1 = SubDistributor1::init(),
			$sd2 = SubDistributor2::init()->useSubDistributors(
				$sd3 = SubDistributor3::init(),
			)
		);

		assertSame($sd1, $this->distributor->getSubDistributor(SubDistributor1::class));
		assertSame($sd2, $this->distributor->getSubDistributor(SubDistributor2::class));
		assertSame($sd3, $sd2->getSubDistributor(SubDistributor3::class));
	}

	public function test_can_set_meta(): void
	{
		$this->distributor->useSubDistributors(
			SubDistributor1::init(),
			$sd = SubDistributor2::init()->useSubDistributors(
				SubDistributor3::init(),
			)
		);

		$meta = new Meta();
		$fakeMeta = new Meta();

		$this->distributor->setMeta($fakeMeta);
		$this->distributor->setMeta($meta);

		assertSame(
			$meta,
			$this->distributor->getSubDistributor(SubDistributor1::class)->getMeta()
		);
		assertSame(
			$meta,
			$this->distributor->getSubDistributor(SubDistributor2::class)->getMeta()
		);
		assertSame(
			$meta,
			$sd->getSubDistributor(SubDistributor3::class)->getMeta()
		);

		assertNotSame($fakeMeta, $sd->getSubDistributor(SubDistributor3::class)->getMeta());
	}

	public function test_polling_subDistributors_1(): void
	{
		$element = self::createStub(Element::class);

		$subDistributor = $this->createMock(AbstractDistributor::class);
		$subDistributor->expects($this->once())
			->method('canHandle')
			->with($this->identicalTo($element))
			->willReturn(false);

		$this->distributor->useSubDistributors($subDistributor);

		$result = (new ReflectionMethod($this->distributor, 'pollSubDistributors'))
			->invoke($this->distributor, $element);

		assertFalse($result);
	}

	public function test_polling_subDistributors_2(): void
	{
		$element = self::createStub(Element::class);

		$subDistributor1 = Mockery::mock(SubDistributor1::class);
		$subDistributor1->shouldReceive('canHandle')->once()->andReturn(true);
		$subDistributor1->shouldAllowMockingProtectedMethods()
			->shouldReceive('pollSubDistributors')
			->once()
			->andReturn(true);
		$subDistributor1->shouldReceive('handle')->never();

		$subDistributor2 = Mockery::mock(SubDistributor2::class);
		$subDistributor2->shouldReceive('canHandle')->never();

		$this->distributor->useSubDistributors($subDistributor1, $subDistributor2);

		$result = (new ReflectionMethod($this->distributor, 'pollSubDistributors'))
			->invoke($this->distributor, $element);

		assertTrue($result);
	}

	public function test_polling_subDistributors_3(): void
	{
		$element = self::createStub(Element::class);

		$subDistributor = Mockery::mock(SubDistributor1::class);
		$subDistributor->shouldReceive('canHandle')->once()->andReturn(true)->ordered();
		$subDistributor->shouldAllowMockingProtectedMethods()
			->shouldReceive('pollSubDistributors')
			->once()
			->andReturn(false)->ordered();
		$subDistributor->shouldReceive('handle')->once()->ordered();

		$this->distributor->useSubDistributors($subDistributor);

		(new ReflectionMethod($this->distributor, 'pollSubDistributors'))
			->invoke($this->distributor, $element);
	}
}