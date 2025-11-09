<?php
/**
 * File containing DownloadUpgradeStrategy class.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdater\Strategy;

use CodeKaizen\WPPackageAutoUpdater\Contract\Client\Downloader\FileDownloaderClientContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\DownloadUpgradeStrategyContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PackageMetaProviderContract;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Strategy for handling package downloads during upgrades.
 */
class DownloadUpgradeStrategy implements DownloadUpgradeStrategyContract {

	/**
	 * Local package meta provider.
	 *
	 * @var PackageMetaProviderContract
	 */
	protected PackageMetaProviderContract $localPackageMetaProvider;

	/**
	 * Remote package meta provider.
	 *
	 * @var PackageMetaProviderContract
	 */
	protected PackageMetaProviderContract $remotePackageMetaProvider;

	/**
	 * File downloader client.
	 *
	 * @var FileDownloaderClientContract
	 */
	protected FileDownloaderClientContract $fileDownloader;

	/**
	 * Logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param PackageMetaProviderContract  $localPackageMetaProvider  Local package meta provider.
	 * @param PackageMetaProviderContract  $remotePackageMetaProvider Remote package meta provider.
	 * @param FileDownloaderClientContract $fileDownloader            File downloader client.
	 * @param LoggerInterface              $logger                    Logger instance.
	 */
	public function __construct(
		PackageMetaProviderContract $localPackageMetaProvider,
		PackageMetaProviderContract $remotePackageMetaProvider,
		FileDownloaderClientContract $fileDownloader,
		LoggerInterface $logger
	) {
		$this->localPackageMetaProvider  = $localPackageMetaProvider;
		$this->remotePackageMetaProvider = $remotePackageMetaProvider;
		$this->fileDownloader            = $fileDownloader;
		$this->logger                    = $logger;
	}

	/**
	 * Downloads an upgrade package if appropriate.
	 *
	 * @param bool|string  $reply       Whether to bail without returning the package or path to downloaded file.
	 * @param string       $package     The package file name.
	 * @param mixed        $upgrader    The WP_Upgrader instance.
	 * @param array<mixed> $hookExtra   Extra arguments passed to hooked filters.
	 *
	 * @return bool|string False to use default upgrade process, or path to downloaded file.
	 */
	public function downloadUpgrade( $reply, string $package, $upgrader, array $hookExtra ): bool|string {
		try {
			$this->logger->debug( 'Checking if we should handle download for package: ' . $package );
			$downloadUrl = $this->remotePackageMetaProvider->getDownloadUrl();
			// If the package URL matches our download URL, handle it.
			if ( $package === $downloadUrl ) {
				// Get the download URL from our remote provider.
				$this->logger->debug( 'Handling download for URL: ' . $downloadUrl );
				if ( $this->fileDownloader->getFileName() === null ) {
					$this->fileDownloader->download();
				}
				$reply = $this->fileDownloader->getFileName() ?? false;
			}
		} catch ( Throwable $e ) {
			$reply = false;
			$this->logger->error( 'Error in DownloadUpgradeStrategy: ' . $e->getMessage() );
		} finally {
			return $reply;
		}
	}
}
