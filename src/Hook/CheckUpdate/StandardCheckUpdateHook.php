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
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckUpdateStrategyContract;
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
class StandardCheckUpdateHook implements InitializerContract, CheckUpdateStrategyContract {

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
			$localPackageMetaValueService  = $this->localPackageMetaValueServiceFactory->create();
			$remotePackageMetaValueService = $this->remotePackageMetaValueServiceFactory->create();
			$localPackageMetaValue         = $localPackageMetaValueService->getPackageMeta();
			$remotePackageMetaValue        = $remotePackageMetaValueService->getPackageMeta();
			$standardClassFactory          = new StandardCheckUpdateObjectFactory(
				$localPackageMetaValue,
				$remotePackageMetaValue
			);
			$this->logger->debug(
				'Checking for updates',
				[
					'fullSlug'  => $localPackageMetaValue->getFullSlug(),
					'transient' => $transient,
				]
			);
			if ( empty( $transient->checked ) ) {
				$this->logger->debug( 'No checked packages in transient, skipping' );
				return $transient;
			}
			$localVersion  = $localPackageMetaValue->getVersion();
			$remoteVersion = $remotePackageMetaValue->getVersion();

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
			return $transient;
		}
		$this->logger->debug( 'Exiting StandardCheckUpdateHook::checkUpdate', [ 'transient' => $transient ] );
		return $transient;
	}
}
