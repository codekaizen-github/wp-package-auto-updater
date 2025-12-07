<?php
/**
 * Test file for DownloadUpgradeHook.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\DownloadUpgrade
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\DownloadUpgrade;

use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;
use CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade\DownloadUpgradeHook;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Exception;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test class for DownloadUpgradeHook.
 */
class DownloadUpgradeHookTest extends TestCase {
	/**
	 * Test that downloadUpgrade runs to completion without exception.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testDownloadUpgradeRunsToCompletion(): void {
		// Mock the dependencies.
		$localFactory  = Mockery::mock( PackageMetaValueServiceContract::class );
		$localProvider = Mockery::mock( PackageMetaValueContract::class );
		$localProvider->shouldReceive( 'getFullSlug' )->andReturn( 'plugin/full-slug' );
		$localFactory->shouldReceive( 'create' )->andReturn( $localProvider );
		$transientAccessor = Mockery::mock( MixedAccessorContract::class );
		$transientAccessor->shouldReceive( 'get' )->andReturn( [] );
		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldNotReceive( 'error' );

		// Overload hard dependencies.
		// phpcs:disable Generic.Files.LineLength.TooLong
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProviderFactory' )
			->shouldReceive( 'create' )->andReturn(
				Mockery::mock(
					[
						'getDownloadURL' => 'https://example.com/download.zip',
					]
				)
			);
		// phpcs:enable Generic.Files.LineLength.TooLong
		Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Provider\WordPress\Transient\TransientWordPressProvider'
		);
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Client\Downloader\FileDownloaderClient' );
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Strategy\DownloadUpgradeStrategy' )
			->shouldReceive( 'downloadUpgrade' )->andReturn( 'downloaded-file.zip' );

		$sut = new DownloadUpgradeHook( $localFactory, $transientAccessor, $httpOptions, $logger );

		// Act.
		$result = $sut->downloadUpgrade( false, 'any-package', null, [] );

		// Assert.
		$this->assertSame( 'downloaded-file.zip', $result );
	}

	/**
	 * Test init method adds filter.
	 *
	 * @return void
	 */
	public function testInitAddsFilter(): void {
		// Mock the dependencies.
		$localFactory      = Mockery::mock( PackageMetaValueServiceContract::class );
		$transientAccessor = Mockery::mock( MixedAccessorContract::class );
		$transientAccessor->shouldReceive( 'get' )->andReturn( [] );
		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );

		$sut = new DownloadUpgradeHook( $localFactory, $transientAccessor, $httpOptions, $logger );
		// Set up expectations.
		WP_Mock::expectFilterAdded(
			'upgrader_pre_download',
			[ $sut, 'downloadUpgrade' ],
			10,
			4
		);

		// Act.
		$sut->init();

		// Assert.
		$this->assertConditionsMet();
	}

	/**
	 * Test exception handling in downloadUpgrade.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testExceptionHandlingInDownloadUpgrade(): void {
		// Mock the dependencies.
		$error = new Exception( 'Test exception' );

		$localFactory = Mockery::mock( PackageMetaValueServiceContract::class );
		$localFactory->shouldReceive( 'create' )->andThrow( $error );

		$transientAccessor = Mockery::mock( MixedAccessorContract::class );
		$transientAccessor->shouldReceive( 'get' )->andReturn( [] );

		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'error' )
			->with( 'Error in DownloadUpgradeHook: Test exception' )
			->once();
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );

		$sut = new DownloadUpgradeHook( $localFactory, $transientAccessor, $httpOptions, $logger );

		// Act.
		$result = $sut->downloadUpgrade( false, 'any-package', null, [] );

		// Assert.
		$this->assertFalse( $result );
	}
}
