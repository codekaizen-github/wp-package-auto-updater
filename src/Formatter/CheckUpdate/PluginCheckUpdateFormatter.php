<?php
/**
 * File containing PluginCheckUpdateFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate
 * @subpackage CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate\PluginCheckUpdateMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;

/**
 * PluginCheckUpdateFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate
 */
class PluginCheckUpdateFormatter implements CheckUpdateFormatterContract {
	/**
	 * The local plugin package meta provider.
	 *
	 * @var PluginPackageMetaContract
	 */
	protected PluginPackageMetaContract $localPackageMetaProvider;
	/**
	 * The remote plugin package meta provider.
	 *
	 * @var PluginPackageMetaContract
	 */
	protected PluginPackageMetaContract $remotePackageMetaProvider;
	/**
	 * Constructor.
	 *
	 * @param PluginPackageMetaContract $localPackageMetaProvider  The local package meta provider.
	 * @param PluginPackageMetaContract $remotePackageMetaProvider The remote package meta provider.
	 */
	public function __construct(
		PluginPackageMetaContract $localPackageMetaProvider,
		PluginPackageMetaContract $remotePackageMetaProvider
	) {
		$this->localPackageMetaProvider  = $localPackageMetaProvider;
		$this->remotePackageMetaProvider = $remotePackageMetaProvider;
		// Constructor can be empty or used for dependency injection if needed in the future.
	}

	/**
	 * Format For Check Update.
	 *
	 * @param array<string, mixed> $response The original response array.
	 *
	 * @return array<string, mixed> The formatted response with update information.
	 */
	public function formatForCheckUpdate(
		array $response,
	): array {
		$metaObject = new PluginCheckUpdateMetaObject( $this->remotePackageMetaProvider );
		$response[ $this->localPackageMetaProvider->getFullSlug() ] = $metaObject;
		return $response;
	}
}
