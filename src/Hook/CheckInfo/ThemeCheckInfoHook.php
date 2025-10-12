<?php
/**
 * File containing ThemeCheckInfoHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckInfoStrategyContract;
use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo\ThemeCheckInfoFormatter;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckInfoStrategy;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;

/**
 * ThemeCheckInfoHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo
 */
class ThemeCheckInfoHook implements InitializerContract, CheckInfoStrategyContract {

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
		add_filter( 'themes_api', array( $this, 'checkInfo' ), 10, 3 );
	}
	/**
	 * Check theme info and inject custom data.
	 *
	 * @param bool                  $result Always false initially.
	 * @param array<string, string> $action The type of information being requested.
	 * @param object                $arg    The arguments passed to the API request.
	 *
	 * @return bool|object                  False if no action taken or object with info.
	 */
	public function checkInfo( bool $result, array $action, object $arg ): bool|object {
		$formatter = new ThemeCheckInfoFormatter( $this->remotePackageMetaProviderFactory->create() );

		$checkInfo = new CheckInfoStrategy(
			$this->localPackageMetaProviderFactory->create(),
			$formatter,
			$this->logger
		);

		return $checkInfo->checkInfo( $result, $action, $arg );
	}
}
