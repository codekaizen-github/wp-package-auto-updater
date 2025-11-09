<?php
/**
 * File containing DownloadUpgradeHook class.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade
 */

namespace CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade;

use CodeKaizen\WPPackageAutoUpdater\Contract\Client\Downloader\FileDownloaderClientContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\DownloadUpgradeStrategyContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageAutoUpdater\Strategy\DownloadUpgradeStrategy;
use Psr\Log\LoggerInterface;

/**
 * Hook for handling package downloads during upgrades.
 */
class DownloadUpgradeHook implements InitializerContract, DownloadUpgradeStrategyContract {

	/**
	 * The local package meta provider factory.
	 *
	 * @var PluginPackageMetaProviderFactoryContract
	 */
	protected PluginPackageMetaProviderFactoryContract $localPackageMetaProviderFactory;

	/**
	 * The remote package meta provider factory.
	 *
	 * @var PluginPackageMetaProviderFactoryContract
	 */
	protected PluginPackageMetaProviderFactoryContract $remotePackageMetaProviderFactory;

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
	 * @param PluginPackageMetaProviderFactoryContract $localPackageMetaProviderFactory  Local provider factory.
	 * @param PluginPackageMetaProviderFactoryContract $remotePackageMetaProviderFactory Remote provider factory.
	 * @param FileDownloaderClientContract             $fileDownloader                   File downloader client.
	 * @param LoggerInterface                          $logger                          Logger instance.
	 */
	public function __construct(
		PluginPackageMetaProviderFactoryContract $localPackageMetaProviderFactory,
		PluginPackageMetaProviderFactoryContract $remotePackageMetaProviderFactory,
		FileDownloaderClientContract $fileDownloader,
		LoggerInterface $logger
	) {
		$this->localPackageMetaProviderFactory  = $localPackageMetaProviderFactory;
		$this->remotePackageMetaProviderFactory = $remotePackageMetaProviderFactory;
		$this->fileDownloader                   = $fileDownloader;
		$this->logger                           = $logger;
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
			$localPackageMetaProvider  = $this->localPackageMetaProviderFactory->create();
			$remotePackageMetaProvider = $this->remotePackageMetaProviderFactory->create();

			$downloadStrategy = new DownloadUpgradeStrategy(
				$localPackageMetaProvider,
				$remotePackageMetaProvider,
				$this->fileDownloader,
				$this->logger
			);

			return $downloadStrategy->downloadUpgrade( $reply, $package, $upgrader, $hookExtra );
		} catch ( \Throwable $e ) {
			$this->logger->error( 'Error in DownloadUpgradeHook: ' . $e->getMessage() );
			return false;
		}
	}
}
