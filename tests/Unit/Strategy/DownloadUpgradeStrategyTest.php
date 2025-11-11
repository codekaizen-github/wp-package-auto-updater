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
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Test class for DownloadUpgradeStrategy.
 */
class DownloadUpgradeStrategyTest extends TestCase {
	/**
	 * Mock check update package meta provider.
	 *
	 * @var \CodeKaizen\WPPackageAutoUpdater\Contract\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProviderContract|Mockery\MockInterface
	 */
	private $checkUpdatePackageMetaProvider;

	/**
	 * The system under test.
	 *
	 * @var DownloadUpgradeStrategy
	 */
	private $sut;


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
		// phpcs:disable Generic.Files.LineLength.TooLong
		$this->checkUpdatePackageMetaProvider = Mockery::mock(
			\CodeKaizen\WPPackageAutoUpdater\Contract\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProviderContract::class
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$this->fileDownloader = Mockery::mock( FileDownloaderClientContract::class );
		$this->logger         = Mockery::mock( LoggerInterface::class );

		$this->logger->shouldReceive( 'debug' )->byDefault();
		$this->logger->shouldReceive( 'error' )->byDefault();

		$this->sut = new DownloadUpgradeStrategy(
			$this->checkUpdatePackageMetaProvider,
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

		$this->checkUpdatePackageMetaProvider->shouldReceive( 'getDownloadUrl' )
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

		$this->checkUpdatePackageMetaProvider->shouldReceive( 'getDownloadUrl' )
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

		$this->checkUpdatePackageMetaProvider->shouldReceive( 'getDownloadUrl' )
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
		$error       = new Exception( 'Download failed' );

		$this->checkUpdatePackageMetaProvider->shouldReceive( 'getDownloadUrl' )
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

		$this->fileDownloader->shouldNotReceive( 'download' );

		// Act.
		$result = $this->sut->downloadUpgrade( $reply, 'any-package', null, [] );

		// Assert.
		$this->assertEquals( $reply, $result );
	}
}
