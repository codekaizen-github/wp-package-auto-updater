<?php
/**
 * File containing CheckUpdateFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate
 * @subpackage CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate\CheckUpdateMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;

/**
 * CheckUpdateFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate
 */
class CheckUpdateFormatter implements CheckUpdateFormatterContract {
	/**
	 * The local plugin package meta provider.
	 *
	 * @var PackageMetaContract
	 */
	protected PackageMetaContract $localPackageMetaProvider;
	/**
	 * The remote plugin package meta provider.
	 *
	 * @var PackageMetaContract
	 */
	protected PackageMetaContract $remotePackageMetaProvider;
	/**
	 * Constructor.
	 *
	 * @param PackageMetaContract $localPackageMetaProvider  The local package meta provider.
	 * @param PackageMetaContract $remotePackageMetaProvider The remote package meta provider.
	 */
	public function __construct(
		PackageMetaContract $localPackageMetaProvider,
		PackageMetaContract $remotePackageMetaProvider
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
		$metaObject = new CheckUpdateMetaObject( $this->remotePackageMetaProvider );
		$response[ $this->localPackageMetaProvider->getFullSlug() ] = $metaObject;
		return $response;
	}
}
