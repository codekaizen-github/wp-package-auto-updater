<?php
/**
 * File containing StandardCheckUpdateStrategy class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Strategy\CheckUpdate
 * @subpackage Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdater\Strategy\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckUpdateStrategyContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Psr\Log\LoggerInterface;
use Exception;
use stdClass;

/**
 * Strategy for checking package updates.
 */
/**
 * StandardCheckUpdateStrategy class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Strategy
 */
class StandardCheckUpdateStrategy implements CheckUpdateStrategyContract {

	/**
	 * The local package meta provider.
	 *
	 * @var PackageMetaValueContract
	 */
	protected PackageMetaValueContract $localPackageMetaProvider;

	/**
	 * The remote package meta provider.
	 *
	 * @var PackageMetaValueContract
	 */
	protected PackageMetaValueContract $remotePackageMetaProvider;

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
	 * @param PackageMetaValueContract          $localPackageMetaProvider  The local package meta provider.
	 * @param PackageMetaValueContract          $remotePackageMetaProvider The remote package meta provider.
	 * @param CheckUpdateFormatterContract $formatter                The formatter.
	 * @param LoggerInterface              $logger                   The logger.
	 */
	/**
	 * Constructor.
	 *
	 * @param PackageMetaValueContract     $localPackageMetaProvider Description for localPackageMetaProvider.
	 * @param PackageMetaValueContract     $remotePackageMetaProvider Description for remotePackageMetaProvider.
	 * @param CheckUpdateFormatterContract $formatter Description for formatter.
	 * @param LoggerInterface              $logger Description for logger.
	 *
	 * @return mixed
	 */
	public function __construct(
		PackageMetaValueContract $localPackageMetaProvider,
		PackageMetaValueContract $remotePackageMetaProvider,
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
	/**
	 * Check for updates.
	 *
	 * @param stdClass $transient Description for transient.
	 *
	 * @return stdClass The modified transient object with update information.
	 */
	public function checkUpdate( stdClass $transient ): stdClass {
		$this->logger->debug(
			'Checking for updates',
			[
				'fullSlug'  => $this->localPackageMetaProvider->getFullSlug(),
				'transient' => $transient,
			]
		);
		if ( empty( $transient->checked ) ) {
			$this->logger->debug( 'No checked packages in transient, skipping' );
			return $transient;
		}
		try {
			$localVersion  = $this->localPackageMetaProvider->getVersion();
			$remoteVersion = $this->remotePackageMetaProvider->getVersion();

			$this->logger->debug(
				'Local version: ' . $localVersion . ', Remote version: ' . $remoteVersion
			);

			if ( null === $localVersion || null === $remoteVersion ) {
				$this->logger->warning( 'Missing version information, skipping update check' );
				return $transient;
			}

			if ( version_compare( $localVersion, $remoteVersion, '<' ) ) {
				// Define the response property if it doesn't exist.
				if ( ! property_exists( $transient, 'response' ) ) {
					$transient->response = [];
				}
				// Ensure $transient->response is an array.
				if ( ! is_array( $transient->response ) ) {
					$transient->response = [];
				}
				$transient->response[ $this->localPackageMetaProvider->getFullSlug() ] =
					$this->formatter->create();
			} else {
				// Define the noUpdate property if it doesn't exist.
				if ( ! property_exists( $transient, 'noUpdate' ) ) {
					$transient->noUpdate = [];
				}
				// Ensure $transient->noUpdate is an array.
				if ( ! is_array( $transient->noUpdate ) ) {
					$transient->noUpdate = [];
				}
				$transient->noUpdate[ $this->localPackageMetaProvider->getFullSlug() ] =
					$this->formatter->create();
			}
		} catch ( Exception $e ) {
			$this->logger->error(
				'Unable to get remote package version: ' . $e->getMessage(),
				[
					'transient' => $transient,
				]
			);
			return $transient;
		}
		$this->logger->debug( 'Exiting StandardCheckUpdateStrategy::checkUpdate', [ 'transient' => $transient ] );
		return $transient;
	}
}
