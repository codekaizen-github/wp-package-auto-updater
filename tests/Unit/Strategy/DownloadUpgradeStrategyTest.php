<?php
/**
 * Test file for DownloadUpgradeStrategy.
 *
 * @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Strategy;

use CodeKaizen\WPPackageAutoUpdater\Contract\Client\Downloader\FileDownloaderClientContract;
use CodeKaizen\WPPackageAutoUpdater\Strategy\DownloadUpgradeStrategy;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PackageMetaProviderContract;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Test class for DownloadUpgradeStrategy.
 */
class DownloadUpgradeStrategyTest extends TestCase {

	/**
	 * The system under test.
	 *
	 * @var DownloadUpgradeStrategy
	 */
	private $sut;

	/**
	 * Mock local package meta provider.
	 *
	 * @var PackageMetaProviderContract|Mockery\MockInterface
	 */
	private $localProvider;

	/**
	 * Mock remote package meta provider.
	 *
	 * @var PackageMetaProviderContract|Mockery\MockInterface
	 */
	private $remoteProvider;

	/**
	 * Mock file downloader.
	 *
	 * @var FileDownloaderClientContract|Mockery\MockInterface
	 */
	private $fileDownloader;

	/**
	 * Mock logger.
	 *
	 * @var LoggerInterface|Mockery\MockInterface
	 */
	private $logger;

	/**
	 * Set up test environment.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->localProvider  = Mockery::mock( PackageMetaProviderContract::class );
		$this->remoteProvider = Mockery::mock( PackageMetaProviderContract::class );
		$this->fileDownloader = Mockery::mock( FileDownloaderClientContract::class );
		$this->logger         = Mockery::mock( LoggerInterface::class );

		$this->logger->shouldReceive( 'debug' )->byDefault();
		$this->logger->shouldReceive( 'error' )->byDefault();

		$this->sut = new DownloadUpgradeStrategy(
			$this->localProvider,
			$this->remoteProvider,
			$this->fileDownloader,
			$this->logger
		);
	}

	/**
	 * Clean up test environment.
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		Mockery::close();
		parent::tearDown();
	}

	/**
	 * Test downloadUpgrade when package matches download URL.
	 *
	 * @return void
	 */
	public function testDownloadUpgradeWhenPackageMatchesDownloadUrl(): void {
		// Arrange.
		$downloadUrl = 'https://example.com/plugin.zip';
		$tempFile    = '/tmp/downloaded-file.zip';

		$this->remoteProvider->shouldReceive( 'getDownloadUrl' )
			->andReturn( $downloadUrl );

		$this->fileDownloader->shouldReceive( 'getFileName' )
			->andReturn( null, $tempFile );

		$this->fileDownloader->shouldReceive( 'download' );

		// Act.
		$result = $this->sut->downloadUpgrade( false, $downloadUrl, null, [] );

		// Assert.
		$this->assertEquals( $tempFile, $result );
	}

	/**
	 * Test downloadUpgrade when file is already downloaded.
	 *
	 * @return void
	 */
	public function testDownloadUpgradeWhenFileAlreadyDownloaded(): void {
		// Arrange.
		$downloadUrl = 'https://example.com/plugin.zip';
		$tempFile    = '/tmp/downloaded-file.zip';

		$this->remoteProvider->shouldReceive( 'getDownloadUrl' )
			->andReturn( $downloadUrl );

		$this->fileDownloader->shouldReceive( 'getFileName' )
			->andReturn( $tempFile );

		$this->fileDownloader->shouldNotReceive( 'download' );

		// Act.
		$result = $this->sut->downloadUpgrade( false, $downloadUrl, null, [] );

		// Assert.
		$this->assertEquals( $tempFile, $result );
	}

	/**
	 * Test downloadUpgrade when package doesn't match download URL.
	 *
	 * @return void
	 */
	public function testDownloadUpgradeWhenPackageDoesNotMatchDownloadUrl(): void {
		// Arrange.
		$downloadUrl = 'https://example.com/plugin.zip';
		$package     = 'https://different.com/plugin.zip';

		$this->remoteProvider->shouldReceive( 'getDownloadUrl' )
			->andReturn( $downloadUrl );

		$this->fileDownloader->shouldNotReceive( 'download' );

		// Act.
		$result = $this->sut->downloadUpgrade( false, $package, null, [] );

		// Assert.
		$this->assertFalse( $result );
	}

	/**
	 * Test downloadUpgrade when an error occurs.
	 *
	 * @return void
	 */
	public function testDownloadUpgradeWhenErrorOccurs(): void {
		// Arrange.
		$downloadUrl = 'https://example.com/plugin.zip';
		$error       = new \Exception( 'Download failed' );

		$this->remoteProvider->shouldReceive( 'getDownloadUrl' )
			->andThrow( $error );

		$this->logger->shouldReceive( 'error' )
			->with( 'Error in DownloadUpgradeStrategy: Download failed' );

		// Act.
		$result = $this->sut->downloadUpgrade( false, $downloadUrl, null, [] );

		// Assert.
		$this->assertFalse( $result );
	}

	/**
	 * Test downloadUpgrade when reply is already set.
	 *
	 * @return void
	 */
	public function testDownloadUpgradeWhenReplyIsAlreadySet(): void {
		// Arrange.
		$reply = '/existing/file.zip';

		$this->remoteProvider->shouldReceive( 'getDownloadUrl' );
		$this->fileDownloader->shouldNotReceive( 'download' );

		// Act.
		$result = $this->sut->downloadUpgrade( $reply, 'any-package', null, [] );

		// Assert.
		$this->assertEquals( $reply, $result );
	}
}
