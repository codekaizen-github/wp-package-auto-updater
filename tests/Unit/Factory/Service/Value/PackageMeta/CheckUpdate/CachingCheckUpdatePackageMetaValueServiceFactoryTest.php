<?php
/**
 * Test file for CachingCheckUpdatePackageMetaValueServiceFactory.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Factory\Service\Value\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\CheckUpdate\CachingCheckUpdatePackageMetaValueServiceFactory;
use CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\CheckUpdate\CachingCheckUpdatePackageMetaValueService;
use Mockery;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test class for StandardCheckUpdatePackageMetaValueServiceFactory.
 */
class CachingCheckUpdatePackageMetaValueServiceFactoryTest extends TestCase {
	/**
	 * Test that create() returns a CachingCheckUpdatePackageMetaValueService.
	 *
	 * @return void
	 */
	public function testCreateReturnsCachingCheckUpdatePackageMetaValueService(): void {
		$accessor = Mockery::mock( MixedAccessorContract::class );
		$fullSlug = 'test-plugin/test-plugin.php';
		$logger   = Mockery::mock( LoggerInterface::class );

		$sut    = new CachingCheckUpdatePackageMetaValueServiceFactory( $accessor, $fullSlug, $logger );
		$result = $sut->create();

		$this->assertInstanceOf( CachingCheckUpdatePackageMetaValueService::class, $result );
	}
}
