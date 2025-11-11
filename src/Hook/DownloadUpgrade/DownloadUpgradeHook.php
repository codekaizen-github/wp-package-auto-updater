<?php
/**
 * File containing DownloadUpgradeHook class.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade
 */

namespace CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade;

use CodeKaizen\WPPackageAutoUpdater\Contract\Client\Downloader\FileDownloaderClientContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Contract\Factory\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\DownloadUpgradeStrategyContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Strategy\DownloadUpgradeStrategy;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Hook for handling package downloads during upgrades.
 */
class DownloadUpgradeHook implements InitializerContract, DownloadUpgradeStrategyContract {

	/**
	 * The CheckUpdate package meta provider factory.
	 *
	 * @var CheckUpdatePackageMetaProviderFactoryContract
	 */
	protected CheckUpdatePackageMetaProviderFactoryContract $checkUpdatePackageMetaProviderFactory;

	/**
	 * The file downloader client.
	 *
	 * @var FileDownloaderClientContract
	 */
	protected FileDownloaderClientContract $fileDownloader;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param CheckUpdatePackageMetaProviderFactoryContract $checkUpdatePackageMetaProviderFactory CheckUpdate.
	 * @param FileDownloaderClientContract                  $fileDownloader                   File downloader client.
	 * @param LoggerInterface                               $logger                          Logger instance.
	 */
	public function __construct(
		CheckUpdatePackageMetaProviderFactoryContract $checkUpdatePackageMetaProviderFactory,
		FileDownloaderClientContract $fileDownloader,
		LoggerInterface $logger
	) {
		$this->checkUpdatePackageMetaProviderFactory = $checkUpdatePackageMetaProviderFactory;
		$this->fileDownloader                        = $fileDownloader;
		$this->logger                                = $logger;
	}

	/**
	 * Initialize the component.
	 *
	 * @return void
	 */
	public function init(): void {
		add_filter( 'upgrader_pre_download', array( $this, 'downloadUpgrade' ), 10, 4 );
	}

	/**
	 * Handle the package download.
	 *
	 * @param bool|string  $reply      Whether to bail without returning the package or path to downloaded file.
	 * @param string       $package    The package file name.
	 * @param mixed        $upgrader   The WP_Upgrader instance.
	 * @param array<mixed> $hookExtra  Extra arguments passed to hooked filters.
	 *
	 * @return bool|string False to use default upgrade process, or path to downloaded file.
	 */
	public function downloadUpgrade( $reply, string $package, $upgrader, array $hookExtra ): bool|string {
		try {
			$downloadStrategy = new DownloadUpgradeStrategy(
				$this->checkUpdatePackageMetaProviderFactory->create(),
				$this->fileDownloader,
				$this->logger
			);

			$reply = $downloadStrategy->downloadUpgrade( $reply, $package, $upgrader, $hookExtra );
		} catch ( Throwable $e ) {
			$reply = false;
			$this->logger->error( 'Error in DownloadUpgradeHook: ' . $e->getMessage() );
		} finally {
			return $reply;
		}
	}
}
