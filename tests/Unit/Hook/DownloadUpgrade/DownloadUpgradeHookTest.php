<?php
/**
 * Test file for DownloadUpgradeHook.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\DownloadUpgrade
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\DownloadUpgrade;

use CodeKaizen\WPPackageAutoUpdater\Contract\Client\Downloader\FileDownloaderClientContract;
use CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade\DownloadUpgradeHook;
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
	 * Test init method adds filter.
	 *
	 * @return void
	 */
	public function testInitAddsFilter(): void {
		// Mock the dependencies.
		$localFactory = Mockery::mock( PluginPackageMetaProviderFactoryContract::class );
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

		$localFactory = Mockery::mock( PluginPackageMetaProviderFactoryContract::class );
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
