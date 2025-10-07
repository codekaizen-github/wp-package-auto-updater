<?php

namespace CodeKaizen\WPPackageAutoUpdater\Strategy;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckUpdateStrategyContract as StrategyCheckUpdateStrategyContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use Psr\Log\LoggerInterface;
use Exception;
use stdClass;

/**
 * Strategy for checking package updates.
 */
class CheckUpdateStrategy implements StrategyCheckUpdateStrategyContract {

	/**
	 * The local package meta provider.
	 *
	 * @var PackageMetaContract
	 */
	protected PackageMetaContract $localPackageMetaProvider;

	/**
	 * The remote package meta provider.
	 *
	 * @var PackageMetaContract
	 */
	protected PackageMetaContract $remotePackageMetaProvider;

	/**
	 * The formatter.
	 *
	 * @var CheckUpdateFormatterContract
	 */
	protected CheckUpdateFormatterContract $formatter;

	/**
	 * The logger.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param PackageMetaContract          $localPackageMetaProvider  The local package meta provider.
	 * @param PackageMetaContract          $remotePackageMetaProvider The remote package meta provider.
	 * @param CheckUpdateFormatterContract $formatter                The formatter.
	 * @param LoggerInterface              $logger                   The logger.
	 */
	public function __construct(
		PackageMetaContract $localPackageMetaProvider,
		PackageMetaContract $remotePackageMetaProvider,
		CheckUpdateFormatterContract $formatter,
		LoggerInterface $logger
	) {
		$this->localPackageMetaProvider  = $localPackageMetaProvider;
		$this->remotePackageMetaProvider = $remotePackageMetaProvider;
		$this->formatter                 = $formatter;
		$this->logger                    = $logger;
	}

	/**
	 * Check for package updates.
	 *
	 * @param stdClass $transient The transient object containing update data.
	 * @return stdClass           The modified transient object.
	 */
	public function checkUpdate( stdClass $transient ): stdClass {
		$this->logger->debug( 'Checking for updates ' . $this->localPackageMetaProvider->getFullSlug() );
		if ( empty( $transient->checked ) ) {
			$this->logger->debug( 'No checked packages in transient, skipping' );
			return $transient;
		}
		try {
			$localVersion  = $this->localPackageMetaProvider->getVersion();
			$remoteVersion = $this->remotePackageMetaProvider->getVersion();

			if ( null === $localVersion || null === $remoteVersion ) {
				$this->logger->warning( 'Missing version information, skipping update check' );
				return $transient;
			}

			if ( version_compare( $localVersion, $remoteVersion, '<' ) ) {
				// Define the response property if it doesn't exist
				if ( ! property_exists( $transient, 'response' ) ) {
					$transient->response = [];
				}
				// Ensure $transient->response is an array
				if ( ! is_array( $transient->response ) ) {
					$transient->response = [];
				}
				$transient->response = $this->formatter->formatForCheckUpdate(
					$transient->response,
					$this->localPackageMetaProvider,
					$this->remotePackageMetaProvider
				);
			} else {
				// Define the no_update property if it doesn't exist
				if ( ! property_exists( $transient, 'no_update' ) ) {
					$transient->no_update = [];
				}
				// Ensure $transient->no_update is an array
				if ( ! is_array( $transient->no_update ) ) {
					$transient->no_update = [];
				}
				$transient->no_update = $this->formatter->formatForCheckUpdate(
					$transient->no_update,
					$this->localPackageMetaProvider,
					$this->remotePackageMetaProvider
				);
			}
		} catch ( Exception $e ) {
			$this->logger->error( 'Unable to get remote package version: ' . $e->getMessage() );
			return $transient;
		}
		return $transient;
	}
}
