<?php
/**
 * File containing PluginCheckUpdateHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate
 * @subpackage CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate;

use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckUpdateStrategyContract;
use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate\CheckUpdateFormatter;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckUpdateStrategy;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
use stdClass;
use Throwable;

/**
 * PluginCheckUpdateHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate
 */
class PluginCheckUpdateHook implements InitializerContract, CheckUpdateStrategyContract {

	/**
	 * The local package meta provider factory.
	 *
	 * @var PluginPackageMetaValueServiceContract
	 */
	protected PluginPackageMetaValueServiceContract $localPackageMetaProviderFactory;

	/**
	 * The remote package meta provider factory.
	 *
	 * @var PluginPackageMetaValueServiceContract
	 */
	protected PluginPackageMetaValueServiceContract $remotePackageMetaProviderFactory;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;
	/**
	 * Constructor.
	 *
	 * @param PluginPackageMetaValueServiceContract $localPackageMetaProviderFactory Local provider factory.
	 * @param PluginPackageMetaValueServiceContract $remotePackageMetaProviderFactory Remote provider factory.
	 * @param LoggerInterface                       $logger Logger instance.
	 */
	public function __construct(
		PluginPackageMetaValueServiceContract $localPackageMetaProviderFactory,
		PluginPackageMetaValueServiceContract $remotePackageMetaProviderFactory,
		LoggerInterface $logger
	) {
		$this->localPackageMetaProviderFactory  = $localPackageMetaProviderFactory;
		$this->remotePackageMetaProviderFactory = $remotePackageMetaProviderFactory;
		$this->logger                           = $logger;
	}
	/**
	 * Initialize the component.
	 *
	 * @return void
	 */
	public function init(): void {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'checkUpdate' ) );
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
				'Entering PluginCheckUpdateHook::checkUpdate',
				[
					'transient' => $transient,
				]
			);
			$localPackageMetaProvider  = $this->localPackageMetaProviderFactory->create();
			$remotePackageMetaProvider = $this->remotePackageMetaProviderFactory->create();
			$formatter                 = new CheckUpdateFormatter(
				$localPackageMetaProvider,
				$remotePackageMetaProvider
			);

			$checkUpdate = new CheckUpdateStrategy(
				$localPackageMetaProvider,
				$remotePackageMetaProvider,
				$formatter,
				$this->logger
			);

			$transient = $checkUpdate->checkUpdate( $transient );
		} catch ( Throwable $e ) {
			$this->logger->error( 'Error in PluginCheckUpdateHook: ' . $e->getMessage() );
		}
		$this->logger->debug( 'Exiting PluginCheckUpdateHook::checkUpdate', [ 'transient' => $transient ] );
		return $transient;
	}
}
