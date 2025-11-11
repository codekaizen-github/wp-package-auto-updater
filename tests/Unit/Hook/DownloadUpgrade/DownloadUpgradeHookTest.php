<?php
/**
 * Test file for DownloadUpgradeHook.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\DownloadUpgrade
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\DownloadUpgrade;

use CodeKaizen\WPPackageAutoUpdater\Contract\Client\Downloader\FileDownloaderClientContract;
use CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade\DownloadUpgradeHook;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Provider\PackageMeta\PackageMetaProviderFactoryContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PackageMetaProviderContract;
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
		$localFactory  = Mockery::mock( PackageMetaProviderFactoryContract::class );
		$localProvider = Mockery::mock( PackageMetaProviderContract::class );
		$localProvider->shouldReceive( 'getFullSlug' )->andReturn( 'plugin/full-slug' );
		$localFactory->shouldReceive( 'create' )->andReturn( $localProvider );
		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );
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

		$sut = new DownloadUpgradeHook( $localFactory, $httpOptions, $logger );

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
		$localFactory = Mockery::mock( PackageMetaProviderFactoryContract::class );
		$httpOptions  = [];
		$logger       = Mockery::mock( LoggerInterface::class );

		$sut = new DownloadUpgradeHook( $localFactory, $httpOptions, $logger );

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

		$localFactory = Mockery::mock( PackageMetaProviderFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andThrow( $error );

		$httpOptions = [];
		$logger      = Mockery::mock( LoggerInterface::class );

		$sut = new DownloadUpgradeHook( $localFactory, $httpOptions, $logger );

		$logger->shouldReceive( 'error' )
			->with( 'Error in DownloadUpgradeHook: Test exception' )
			->once();

		// Act.
		$result = $sut->downloadUpgrade( false, 'any-package', null, [] );

		// Assert.
		$this->assertFalse( $result );
	}
}
