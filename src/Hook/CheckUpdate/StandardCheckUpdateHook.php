<?php
/**
 * File containing StandardCheckUpdateHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate
 * @subpackage CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate;

use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Hook\CheckUpdateHookContract;
use CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckUpdate\StandardCheckUpdateObjectFactory;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PackageMetaValueServiceFactoryContract;
use stdClass;
use Throwable;

/**
 * StandardCheckUpdateHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate
 */
class StandardCheckUpdateHook implements InitializerContract, CheckUpdateHookContract {

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected string $hookName;

	/**
	 * The local package meta provider factory.
	 *
	 * @var PackageMetaValueServiceFactoryContract
	 */
	protected PackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactory;

	/**
	 * The remote package meta provider factory.
	 *
	 * @var PackageMetaValueServiceFactoryContract
	 */
	protected PackageMetaValueServiceFactoryContract $remotePackageMetaValueServiceFactory;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;
	/**
	 * Constructor.
	 *
	 * @param string                                 $hookName Hook.
	 * @param PackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactory Local factory.
	 * @param PackageMetaValueServiceFactoryContract $remotePackageMetaValueServiceFactory Remote factory.
	 * @param LoggerInterface                        $logger Logger instance.
	 */
	public function __construct(
		string $hookName,
		PackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactory,
		PackageMetaValueServiceFactoryContract $remotePackageMetaValueServiceFactory,
		LoggerInterface $logger
	) {
		$this->hookName                             = $hookName;
		$this->localPackageMetaValueServiceFactory  = $localPackageMetaValueServiceFactory;
		$this->remotePackageMetaValueServiceFactory = $remotePackageMetaValueServiceFactory;
		$this->logger                               = $logger;
	}
	/**
	 * Initialize the component.
	 *
	 * @return void
	 */
	public function init(): void {
		add_filter( $this->hookName, array( $this, 'checkUpdate' ) );
	}
	/**
	 * Check for updates.
	 *
	 * @param stdClass $transient WordPress update transient.
	 *
	 * @return stdClass Modified transient with update information.
	 */
	public function checkUpdate( stdClass $transient ): stdClass {
		try {
			$this->logger->debug( 'Entering StandardCheckUpdateHook::checkUpdate', [ 'transient' => $transient ] );
			$this->logger->debug(
				'Checking for updates',
				[
					'transient' => $transient,
				]
			);
			if ( empty( $transient->checked ) ) {
				$this->logger->debug( 'No checked packages in transient, skipping' );
				return $transient;
			}

			$localPackageMetaValueService = $this->localPackageMetaValueServiceFactory->create();
			$localPackageMetaValue        = $localPackageMetaValueService->getPackageMeta();

			$localVersion = $localPackageMetaValue->getVersion();

			if ( null === $localVersion ) {
				$this->logger->warning( 'Missing local version information, skipping update check' );
				return $transient;
			}

			$remotePackageMetaValueService = $this->remotePackageMetaValueServiceFactory->create();
			$remotePackageMetaValue        = $remotePackageMetaValueService->getPackageMeta();
			$remoteVersion                 = $remotePackageMetaValue->getVersion();

			if ( null === $remoteVersion ) {
				$this->logger->warning( 'Missing remote version information, skipping update check' );
				return $transient;
			}

			$this->logger->debug(
				'Local version: ' . $localVersion . ', Remote version: ' . $remoteVersion
			);

			$standardClassFactory = new StandardCheckUpdateObjectFactory(
				$remotePackageMetaValue
			);

			if ( version_compare( $localVersion, $remoteVersion, '<' ) ) {
				// Define the response property if it doesn't exist.
				if ( ! property_exists( $transient, 'response' ) ) {
					$transient->response = [];
				}
				// Ensure $transient->response is an array.
				if ( ! is_array( $transient->response ) ) {
					$transient->response = [];
				}
				$transient->response[ $localPackageMetaValue->getFullSlug() ] =
					$standardClassFactory->create();
			} else {
				// Define the noUpdate property if it doesn't exist.
				if ( ! property_exists( $transient, 'noUpdate' ) ) {
					$transient->noUpdate = [];
				}
				// Ensure $transient->noUpdate is an array.
				if ( ! is_array( $transient->noUpdate ) ) {
					$transient->noUpdate = [];
				}
				$transient->noUpdate[ $localPackageMetaValue->getFullSlug() ] =
					$standardClassFactory->create();
			}
		} catch ( Throwable $e ) {
			$this->logger->error(
				'Unable to get remote package version: ' . $e->getMessage(),
				[
					'transient' => $transient,
				]
			);
		}
		$this->logger->debug( 'Exiting StandardCheckUpdateHook::checkUpdate', [ 'transient' => $transient ] );
		return $transient;
	}
}
