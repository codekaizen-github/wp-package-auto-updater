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
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryContract;
use Throwable;

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
	 * @param bool|object $result Always false initially.
	 * @param string      $action The type of information being requested.
	 * @param object      $arg    The arguments passed to the API request.
	 *
	 * @return bool|object                  False if no action taken or object with info.
	 */
	public function checkInfo( bool|object $result, string $action, object $arg ): bool|object {
		try {
			$this->logger->debug(
				'Entering ThemeCheckInfoHook::checkInfo',
				[
					'result' => $result,
					'action' => $action,
					'arg'    => $arg,
				]
			);
			$formatter = new ThemeCheckInfoFormatter( $this->remotePackageMetaProviderFactory->create() );

			$checkInfo = new CheckInfoStrategy(
				$this->localPackageMetaProviderFactory->create(),
				$formatter,
				$this->logger
			);

			$result = $checkInfo->checkInfo( $result, $action, $arg );
		} catch ( Throwable $e ) {
			$this->logger->error( 'Error occurred while checking theme info: ' . $e->getMessage() );
		}
		$this->logger->debug( 'Exiting ThemeCheckInfoHook::checkInfo', [ 'result' => $result ] );
		return $result;
	}
}
