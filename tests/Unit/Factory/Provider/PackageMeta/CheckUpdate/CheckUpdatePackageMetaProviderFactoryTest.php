<?php
/**
 * Unit test for StandardCheckUpdatePackageMetaValueServiceFactory.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Factory\Provider\PackageMeta\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Tests\Unit\Factory\Provider\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Service\Value\PackageMeta\CheckUpdatePackageMetaValueServiceContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValueServiceFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class CheckUpdatePackageMetaProviderFactoryTest
 *
 * @covers \CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValueServiceFactory
 */
class CheckUpdatePackageMetaProviderFactoryTest extends TestCase {
	/**
	 * Test create returns provider for valid data.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testCreateReturnsProviderForValidData(): void {
			$slug     = 'akismet/akismet.php';
			$accessor = Mockery::mock(
				\CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract::class
			);
			$logger   = Mockery::mock( LoggerInterface::class );
			// phpcs:disable Generic.Files.LineLength.TooLong
			$service = Mockery::mock(
				'overload:CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValueService',
				CheckUpdatePackageMetaValueServiceContract::class
			);
			// phpcs:enable Generic.Files.LineLength.TooLong
			// $service should receive accessor, slug, and logger in constructor
			$service->shouldReceive( '__construct' )->with(
				$accessor,
				$slug,
				$logger
			);
			$sut = new StandardCheckUpdatePackageMetaValueServiceFactory( $accessor, $slug, $logger );
			$this->assertInstanceOf( CheckUpdatePackageMetaValueServiceContract::class, $sut->create() );
	}
}
