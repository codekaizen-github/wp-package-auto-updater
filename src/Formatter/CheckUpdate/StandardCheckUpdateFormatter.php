<?php
/**
 * File containing StandardCheckUpdateFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate
 * @subpackage CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate\CheckUpdateMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;

/**
 * StandardCheckUpdateFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate
 */
class StandardCheckUpdateFormatter implements CheckUpdateFormatterContract {
	/**
	 * The local plugin package meta provider.
	 *
	 * @var PackageMetaValueContract
	 */
	protected PackageMetaValueContract $localPackageMetaProvider;
	/**
	 * The remote plugin package meta provider.
	 *
	 * @var PackageMetaValueContract
	 */
	protected PackageMetaValueContract $remotePackageMetaProvider;
	/**
	 * Constructor.
	 *
	 * @param PackageMetaValueContract $localPackageMetaProvider  The local package meta provider.
	 * @param PackageMetaValueContract $remotePackageMetaProvider The remote package meta provider.
	 */
	public function __construct(
		PackageMetaValueContract $localPackageMetaProvider,
		PackageMetaValueContract $remotePackageMetaProvider
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
