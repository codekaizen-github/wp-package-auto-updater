<?php
/**
 * File containing StandardCheckUpdateArrayFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Array\CheckUpdate
 * @subpackage CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Array\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckUpdate\StandardCheckUpdateStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;

/**
 * StandardCheckUpdateArrayFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Array\CheckUpdate
 */
class StandardCheckUpdateArrayFactory implements CheckUpdateFormatterContract {
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
	 * @return object The formatted response with update information.
	 */
	public function formatForCheckUpdate(): object {
		$metaObject = new StandardCheckUpdateStandardClass( $this->remotePackageMetaProvider );
		return $metaObject;
	}
}
