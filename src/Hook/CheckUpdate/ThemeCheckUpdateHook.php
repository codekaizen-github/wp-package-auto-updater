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
use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate\CheckUpdateFormatter;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckUpdateStrategy;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
use stdClass;

/**
 * ThemeCheckUpdateHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate
 */
class ThemeCheckUpdateHook implements InitializerContract, CheckUpdateStrategyContract {

	/**
	 * The local theme package meta provider factory.
	 *
	 * @var ThemePackageMetaProviderFactoryContract
	 */
	protected ThemePackageMetaProviderFactoryContract $localPackageMetaProviderFactory;

	/**
	 * The remote theme package meta provider factory.
	 *
	 * @var ThemePackageMetaProviderFactoryContract
	 */
	protected ThemePackageMetaProviderFactoryContract $remotePackageMetaProviderFactory;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;
	/**
	 * Constructor.
	 *
	 * @param ThemePackageMetaProviderFactoryContract $localPackageMetaProviderFactory Local provider factory.
	 * @param ThemePackageMetaProviderFactoryContract $remotePackageMetaProviderFactory Remote provider factory.
	 * @param LoggerInterface                         $logger Logger instance.
	 */
	public function __construct(
		ThemePackageMetaProviderFactoryContract $localPackageMetaProviderFactory,
		ThemePackageMetaProviderFactoryContract $remotePackageMetaProviderFactory,
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
		$formatter = new CheckUpdateFormatter(
			$this->localPackageMetaProviderFactory->create(),
			$this->remotePackageMetaProviderFactory->create(),
		);

		$checkUpdate = new CheckUpdateStrategy(
			$this->localPackageMetaProviderFactory->create(),
			$this->remotePackageMetaProviderFactory->create(),
			$formatter,
			$this->logger
		);

		return $checkUpdate->checkUpdate( $transient );
	}
}
