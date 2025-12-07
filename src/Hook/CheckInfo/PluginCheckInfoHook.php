<?php
/**
 * File containing PluginCheckInfoHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckInfoStrategyContract;
use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo\PluginCheckInfoFormatter;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckInfoStrategy;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
use Throwable;

/**
 * PluginCheckInfoHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo
 */
class PluginCheckInfoHook implements InitializerContract, CheckInfoStrategyContract {

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
		add_filter( 'plugins_api', array( $this, 'checkInfo' ), 10, 3 );
	}
	/**
	 * Check plugin info and inject custom data.
	 *
	 * @param bool|object $result Always false initially.
	 * @param string      $action The type of information being requested.
	 * @param object      $arg    The arguments passed to the API request.
	 *
	 * @return bool|object                  False if no action taken or object with info.
	 */
	public function checkInfo( bool|object $result, string $action, object $arg ): bool|object {
		try {
			$this->logger->debug(
				'Entering PluginCheckInfoHook::checkInfo',
				[
					'result' => $result,
					'action' => $action,
					'arg'    => $arg,
				]
			);
			$formatter = new PluginCheckInfoFormatter( $this->remotePackageMetaProviderFactory->create() );

			$checkInfo = new CheckInfoStrategy(
				$this->localPackageMetaProviderFactory->create(),
				$formatter,
				$this->logger
			);

			$result = $checkInfo->checkInfo( $result, $action, $arg );
		} catch ( Throwable $e ) {
			$this->logger->error( 'Error in PluginCheckInfoHook::checkInfo: ' . $e->getMessage() );
		}
		$this->logger->debug( 'Exiting PluginCheckInfoHook::checkInfo', [ 'result' => $result ] );
		return $result;
	}
}
