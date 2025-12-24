<?php
/**
 * File containing StandardDownloadUpgradeHook class.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade
 */

namespace CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade;

use CodeKaizen\WPPackageAutoUpdater\Client\Downloader\FileDownloaderClient;
use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\DownloadUpgradeStrategyContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValueServiceFactory;
use CodeKaizen\WPPackageAutoUpdater\Strategy\DownloadUpgrade\StandardDownloadUpgradeStrategy;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PackageMetaValueServiceFactoryContract;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Hook for handling package downloads during upgrades.
 */
class StandardDownloadUpgradeHook implements InitializerContract, DownloadUpgradeStrategyContract {

	/**
	 * Undocumented variable
	 *
	 * @var PackageMetaValueServiceFactoryContract
	 */
	protected PackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactory;

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
	 * @param PackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactory Local.
	 * @param MixedAccessorContract                  $transientAccessor               Transient accessor.
	 * @param array<string,mixed>                    $httpOptions                    Http options.
	 * @param LoggerInterface                        $logger                        Logger instance.
	 */
	public function __construct(
		PackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactory,
		MixedAccessorContract $transientAccessor,
		array $httpOptions,
		LoggerInterface $logger
	) {
		$this->localPackageMetaValueServiceFactory = $localPackageMetaValueServiceFactory;
		$this->transientAccessor                   = $transientAccessor;
		$this->httpOptions                         = $httpOptions;
		$this->logger                              = $logger;
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
			$this->logger->debug(
				'Entering StandardDownloadUpgradeHook::downloadUpgrade',
				[
					'reply'     => $reply,
					'package'   => $package,
					'upgrader'  => $upgrader,
					'hookExtra' => $hookExtra,
				]
			);
			// If reply is already set (not false), return it immediately.
			if ( $reply ) {
				$this->logger->debug(
					'Reply already set, returning early from StandardDownloadUpgradeStrategy::downloadUpgrade',
					[ 'reply' => $reply ]
				);
				return $reply;
			}
			$localPackageMetaValueService                      = $this->localPackageMetaValueServiceFactory->create();
			$localPackageMetaValue                             = $localPackageMetaValueService->getPackageMeta();
			$standardCheckUpdatePackageMetaValueServiceFactory = new StandardCheckUpdatePackageMetaValueServiceFactory(
				$this->transientAccessor,
				$localPackageMetaValue->getFullSlug(),
				$this->logger
			);
			// phpcs:ignore Generic.Files.LineLength.TooLong
			$checkUpdatePackageMetaValueService = $standardCheckUpdatePackageMetaValueServiceFactory->create();
			$checkUpdatePackageMetaValue        = $checkUpdatePackageMetaValueService->getPackageMeta();
			$downloadURL                        = $checkUpdatePackageMetaValue->getDownloadURL();
			if ( null === $downloadURL ) {
				return $reply;
			}
			$fileDownloader = new FileDownloaderClient(
				$downloadURL,
				$this->httpOptions,
				$this->logger
			);
			$this->logger->debug(
				'Entering StandardDownloadUpgradeStrategy::downloadUpgrade',
				[
					'reply'     => $reply,
					'package'   => $package,
					'upgrader'  => $upgrader,
					'hookExtra' => $hookExtra,
				]
			);

			$this->logger->debug( 'Checking if we should handle download for package: ' . $package );
			$downloadURL = $checkUpdatePackageMetaValue->getDownloadURL();
			// If the package URL matches our download URL, handle it.
			if ( $package === $downloadURL ) {
				// Get the download URL from our remote provider.
				$this->logger->debug( 'Handling download for URL: ' . $downloadURL );
				if ( $fileDownloader->getFileName() === null ) {
					$fileDownloader->download();
				}
				$reply = $fileDownloader->getFileName() ?? false;
			}
		} catch ( Throwable $e ) {
			$reply = false;
			$this->logger->error(
				'Error in StandardDownloadUpgradeHook: ' . $e->getMessage(),
				[ 'exception' => $e ]
			);
		}
		$this->logger->debug( 'Exiting StandardDownloadUpgradeStrategy::downloadUpgrade', [ 'reply' => $reply ] );
		return $reply;
	}
}
