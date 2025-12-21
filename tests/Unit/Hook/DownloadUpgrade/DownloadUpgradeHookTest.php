<?php
/**
 * Test file for StandardDownloadUpgradeHook.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\DownloadUpgrade
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\DownloadUpgrade;

use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Service\Value\PackageMeta\CheckUpdatePackageMetaValueServiceContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Value\PackageMeta\CheckUpdatePackageMetaValueContract;
use CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade\StandardDownloadUpgradeHook;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PackageMetaValueServiceFactoryContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Exception;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Test class for StandardDownloadUpgradeHook.
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
		$localFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localService = Mockery::mock( PackageMetaValueServiceContract::class );
		$localValue   = Mockery::mock( PackageMetaValueContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn( $localService );
		$localService->shouldReceive( 'getPackageMeta' )->andReturn( $localValue );
		$localValue->shouldReceive( 'getFullSlug' )->andReturn( 'plugin/full-slug' );
		$transientAccessor = Mockery::mock( MixedAccessorContract::class );
		$transientAccessor->shouldReceive( 'get' )->andReturn( [] );
		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldNotReceive( 'error' );
		$service = Mockery::mock( CheckUpdatePackageMetaValueServiceContract::class );
		$value   = Mockery::mock( CheckUpdatePackageMetaValueContract::class );
		$service->shouldReceive( 'getPackageMeta' )->andReturn( $value );
		$value->shouldReceive( 'getDownloadURL' )->andReturn( 'https://example.com/download.zip' );
		// Overload hard dependencies.
		// phpcs:disable Generic.Files.LineLength.TooLong
		Mockery::mock( 'overload:CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValueServiceFactory' )
			->shouldReceive( 'create' )->andReturn(
				$service
			);
		// phpcs:enable Generic.Files.LineLength.TooLong
		Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Accessor\Mixed\WordPressTransientProxyMixedAccessor'
		);
		$fileDownloader = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Client\Downloader\FileDownloaderClient'
		);
		$fileDownloader->shouldReceive( 'getFileName' )->andReturn( 'downloaded-file.zip' );
		Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Strategy\DownloadUpgrade\StandardDownloadUpgradeStrategy'
		)->shouldReceive( 'downloadUpgrade' )->andReturn( 'downloaded-file.zip' );
		$sut = new StandardDownloadUpgradeHook( $localFactory, $transientAccessor, $httpOptions, $logger );

		// Act.
		$result = $sut->downloadUpgrade( false, 'https://example.com/download.zip', null, [] );

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
		$localFactory      = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$transientAccessor = Mockery::mock( MixedAccessorContract::class );
		$transientAccessor->shouldReceive( 'get' )->andReturn( [] );
		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );

		$sut = new StandardDownloadUpgradeHook( $localFactory, $transientAccessor, $httpOptions, $logger );
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

		$localFactory = Mockery::mock( PackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andThrow( $error );

		$transientAccessor = Mockery::mock( MixedAccessorContract::class );
		$transientAccessor->shouldReceive( 'get' )->andReturn( [] );

		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'error' )
			->with( 'Error in StandardDownloadUpgradeHook: Test exception', [ 'exception' => $error ] )
			->once();
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );

		$sut = new StandardDownloadUpgradeHook( $localFactory, $transientAccessor, $httpOptions, $logger );

		// Act.
		$result = $sut->downloadUpgrade( false, 'any-package', null, [] );

		// Assert.
		$this->assertFalse( $result );
	}
}
