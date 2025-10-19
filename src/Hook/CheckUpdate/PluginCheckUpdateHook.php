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
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryContract;
use stdClass;

/**
 * PluginCheckUpdateHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate
 */
class PluginCheckUpdateHook implements InitializerContract, CheckUpdateStrategyContract {

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
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;
	/**
	 * Constructor.
	 *
	 * @param PluginPackageMetaProviderFactoryContract $localPackageMetaProviderFactory Local provider factory.
	 * @param PluginPackageMetaProviderFactoryContract $remotePackageMetaProviderFactory Remote provider factory.
	 * @param LoggerInterface                          $logger Logger instance.
	 */
	public function __construct(
		PluginPackageMetaProviderFactoryContract $localPackageMetaProviderFactory,
		PluginPackageMetaProviderFactoryContract $remotePackageMetaProviderFactory,
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
		$localPackageMetaProvider         = $this->localPackageMetaProviderFactory->create();
		$remotePackageMetaProviderFactory = $this->remotePackageMetaProviderFactory->create();
		$formatter                        = new CheckUpdateFormatter(
			$localPackageMetaProvider,
			$remotePackageMetaProviderFactory
		);

		$checkUpdate = new CheckUpdateStrategy(
			$localPackageMetaProvider,
			$remotePackageMetaProviderFactory,
			$formatter,
			$this->logger
		);

		return $checkUpdate->checkUpdate( $transient );
	}
}
