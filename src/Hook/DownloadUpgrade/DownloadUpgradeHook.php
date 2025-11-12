<?php
/**
 * File containing DownloadUpgradeHook class.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade
 */

namespace CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade;

use CodeKaizen\WPPackageAutoUpdater\Client\Downloader\FileDownloaderClient;
use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\DownloadUpgradeStrategyContract;
use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProviderFactory;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Strategy\DownloadUpgradeStrategy;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Provider\PackageMeta\PackageMetaProviderFactoryContract;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Hook for handling package downloads during upgrades.
 */
class DownloadUpgradeHook implements InitializerContract, DownloadUpgradeStrategyContract {

	/**
	 * Undocumented variable
	 *
	 * @var PackageMetaProviderFactoryContract
	 */
	protected PackageMetaProviderFactoryContract $localPackageMetaProviderFactory;

	/**
	 * Undocumented variable
	 *
	 * @var MixedAccessorContract
	 */
	protected MixedAccessorContract $transientAccessor;

	/**
	 * Undocumented variable
	 *
	 * @var array<string,mixed>
	 */
	protected array $httpOptions;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param PackageMetaProviderFactoryContract $localPackageMetaProviderFactory Local.
	 * @param MixedAccessorContract              $transientAccessor               Transient accessor.
	 * @param array<string,mixed>                $httpOptions                    Http options.
	 * @param LoggerInterface                    $logger                        Logger instance.
	 */
	public function __construct(
		PackageMetaProviderFactoryContract $localPackageMetaProviderFactory,
		MixedAccessorContract $transientAccessor,
		array $httpOptions,
		LoggerInterface $logger
	) {
		$this->localPackageMetaProviderFactory = $localPackageMetaProviderFactory;
		$this->transientAccessor               = $transientAccessor;
		$this->httpOptions                     = $httpOptions;
		$this->logger                          = $logger;
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
			$localPackageMetaProvider              = $this->localPackageMetaProviderFactory->create();
			$checkUpdatePackageMetaProviderFactory = new CheckUpdatePackageMetaProviderFactory(
				$this->transientAccessor,
				$localPackageMetaProvider->getFullSlug()
			);
			$checkUpdatePackageMetaProvider        = $checkUpdatePackageMetaProviderFactory->create();
			$downloadURL                           = $checkUpdatePackageMetaProvider->getDownloadURL();
			if ( null === $downloadURL ) {
				return $reply;
			}
			$fileDownloader   = new FileDownloaderClient(
				$downloadURL,
				$this->httpOptions
			);
			$downloadStrategy = new DownloadUpgradeStrategy(
				$checkUpdatePackageMetaProvider,
				$fileDownloader,
				$this->logger
			);

			$reply = $downloadStrategy->downloadUpgrade( $reply, $package, $upgrader, $hookExtra );
		} catch ( Throwable $e ) {
			$reply = false;
			$this->logger->error( 'Error in DownloadUpgradeHook: ' . $e->getMessage() );
		}
		return $reply;
	}
}
