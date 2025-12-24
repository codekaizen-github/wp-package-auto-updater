<?php
/**
 * File containing PluginCheckInfoHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Factory\ObjectFactoryContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Hook\CheckInfoHookContract;
use Psr\Log\LoggerInterface;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PackageMetaValueServiceFactoryContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryContract;
use Throwable;

/**
 * PluginCheckInfoHook class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo
 */
class StandardCheckInfoHook implements InitializerContract, CheckInfoHookContract {

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected string $hookName;

	/**
	 * Undocumented variable
	 *
	 * @var ObjectFactoryContract
	 */
	protected ObjectFactoryContract $remoteCheckInfoObjectFactory;

	/**
	 * The local package meta provider factory.
	 *
	 * @var PackageMetaValueServiceFactoryContract
	 */
	protected PackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactoryContract;

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
	 * @param ObjectFactoryContract                  $remoteCheckInfoObjectFactory Object factory.
	 * @param PackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactoryContract Local factory.
	 * @param LoggerInterface                        $logger Logger instance.
	 */
	public function __construct(
		string $hookName,
		ObjectFactoryContract $remoteCheckInfoObjectFactory,
		PackageMetaValueServiceFactoryContract $localPackageMetaValueServiceFactoryContract,
		LoggerInterface $logger
	) {
		$this->hookName                                    = $hookName;
		$this->remoteCheckInfoObjectFactory                = $remoteCheckInfoObjectFactory;
		$this->localPackageMetaValueServiceFactoryContract = $localPackageMetaValueServiceFactoryContract;
		$this->logger                                      = $logger;
	}
	/**
	 * Initialize the component.
	 *
	 * @return void
	 */
	public function init(): void {
		add_filter( $this->hookName, array( $this, 'checkInfo' ), 10, 3 );
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
				'Entering StandardCheckInfoHook::checkInfo',
				[
					'false'  => $result,
					'action' => $action,
					'arg'    => $arg,
				]
			);

			$localPackageMetaValueService = $this->localPackageMetaValueServiceFactoryContract->create();
			$localPackageMetaValue        = $localPackageMetaValueService->getPackageMeta();

			// phpcs:enable Universal.NamingConventions.NoReservedKeywordParameterNames.falseFound
			// Check if this is for our package.
			if ( ! isset( $arg->slug ) || $arg->slug !== $localPackageMetaValue->getShortSlug() ) {
				$this->logger->debug(
					'Slug does not match local package meta provider. Returning false.',
					[
						'false'  => $result,
						'action' => $action,
						'arg'    => $arg,
					]
				);
				return $result;
			}
			$this->logger->debug( 'Providing package info for: ' . $arg->slug );

			// Get metadata from remote source.
			$meta = $this->remoteCheckInfoObjectFactory->create();

			$this->logger->debug(
				'Returning package info with properties: ' . implode(
					', ',
					array_keys( (array) $meta )
				)
			);

			return $meta;
		} catch ( Throwable $e ) {
			$this->logger->error( 'Error in PluginCheckInfoHook::checkInfo: ' . $e->getMessage() );
		}
		$this->logger->debug( 'Exiting PluginCheckInfoHook::checkInfo', [ 'result' => $result ] );
		return $result;
	}
}
