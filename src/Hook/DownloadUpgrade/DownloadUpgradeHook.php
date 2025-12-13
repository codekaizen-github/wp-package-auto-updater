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
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValueServiceFactory;
use CodeKaizen\WPPackageAutoUpdater\Strategy\DownloadUpgradeStrategy;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PackageMetaValueServiceFactoryContract;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Hook for handling package downloads during upgrades.
 */
class DownloadUpgradeHook implements InitializerContract, DownloadUpgradeStrategyContract {

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
				'Entering DownloadUpgradeHook::downloadUpgrade',
				[
					'reply'     => $reply,
					'package'   => $package,
					'upgrader'  => $upgrader,
					'hookExtra' => $hookExtra,
				]
			);
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
			$fileDownloader   = new FileDownloaderClient(
				$downloadURL,
				$this->httpOptions,
				$this->logger
			);
			$downloadStrategy = new DownloadUpgradeStrategy(
				$checkUpdatePackageMetaValue,
				$fileDownloader,
				$this->logger
			);

			$reply = $downloadStrategy->downloadUpgrade( $reply, $package, $upgrader, $hookExtra );
		} catch ( Throwable $e ) {
			$reply = false;
			$this->logger->error( 'Error in DownloadUpgradeHook: ' . $e->getMessage() );
		}
		$this->logger->debug( 'Exiting DownloadUpgradeHook::downloadUpgrade', [ 'reply' => $reply ] );
		return $reply;
	}
}
