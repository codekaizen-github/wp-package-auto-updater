<?php
/**
 * File containing ThemeCheckUpdateHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate
 * @subpackage CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate;

use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckUpdateStrategyContract;
use CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckUpdate\StandardCheckUpdateStandardClassFactory;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckUpdate\StandardCheckUpdateStrategy;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\ThemePackageMetaValueServiceFactoryContract;
use stdClass;
use Throwable;

/**
 * ThemeCheckUpdateHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate
 */
class ThemeCheckUpdateHook implements InitializerContract, CheckUpdateStrategyContract {

	/**
	 * The local package meta provider factory.
	 *
	 * @var ThemePackageMetaValueServiceFactoryContract
	 */
	protected ThemePackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactory;

	/**
	 * The remote package meta provider factory.
	 *
	 * @var ThemePackageMetaValueServiceFactoryContract
	 */
	protected ThemePackageMetaValueServiceFactoryContract $remotePackageMetaValueServiceFactory;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;
	/**
	 * Constructor.
	 *
	 * @param ThemePackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactory Local factory.
	 * @param ThemePackageMetaValueServiceFactoryContract $remotePackageMetaValueServiceFactory Remote factory.
	 * @param LoggerInterface                             $logger Logger instance.
	 */
	public function __construct(
		ThemePackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactory,
		ThemePackageMetaValueServiceFactoryContract $remotePackageMetaValueServiceFactory,
		LoggerInterface $logger
	) {
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
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'checkUpdate' ) );
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
			$this->logger->debug(
				'Entering ThemeCheckUpdateHook::checkUpdate',
				[
					'transient' => $transient,
				]
			);
			$localPackageMetaValueService  = $this->localPackageMetaValueServiceFactory->create();
			$remotePackageMetaValueService = $this->remotePackageMetaValueServiceFactory->create();
			$localPackageMetaValue         = $localPackageMetaValueService->getPackageMeta();
			$remotePackageMetaValue        = $remotePackageMetaValueService->getPackageMeta();
			$formatter                     = new StandardCheckUpdateStandardClassFactory(
				$localPackageMetaValue,
				$remotePackageMetaValue
			);

			$checkUpdate = new StandardCheckUpdateStrategy(
				$localPackageMetaValue,
				$remotePackageMetaValue,
				$formatter,
				$this->logger
			);

			$transient = $checkUpdate->checkUpdate( $transient );
		} catch ( Throwable $e ) {
			$this->logger->error( 'Error in ThemeCheckUpdateHook: ' . $e->getMessage() );
		}
		$this->logger->debug( 'Exiting ThemeCheckUpdateHook::checkUpdate', [ 'transient' => $transient ] );
		return $transient;
	}
}
