<?php
/**
 * Test file for StandardCheckUpdatePackageMetaValueServiceFactory.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValueServiceFactory;
use CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValueService;
use Mockery;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test class for StandardCheckUpdatePackageMetaValueServiceFactory.
 */
class StandardCheckUpdatePackageMetaValueServiceFactoryTest extends TestCase {
	/**
	 * Test that create() returns a StandardCheckUpdatePackageMetaValueService.
	 *
	 * @return void
	 */
	public function testCreateReturnsStandardCheckUpdatePackageMetaValueService(): void {
		$accessor = Mockery::mock( MixedAccessorContract::class );
		$fullSlug = 'test-plugin/test-plugin.php';
		$logger   = Mockery::mock( LoggerInterface::class );

		$sut    = new StandardCheckUpdatePackageMetaValueServiceFactory( $accessor, $fullSlug, $logger );
		$result = $sut->create();

		$this->assertInstanceOf( StandardCheckUpdatePackageMetaValueService::class, $result );
	}
}
