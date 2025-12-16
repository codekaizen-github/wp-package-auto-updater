<?php
/**
 * File containing StandardCheckUpdateStandardClassFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckUpdate
 * @subpackage CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Factory\ObjectFactoryContract;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckUpdate\StandardCheckUpdateStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;

/**
 * StandardCheckUpdateStandardClassFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckUpdate
 */
class StandardCheckUpdateStandardClassFactory implements ObjectFactoryContract {
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
	public function create(): object {
		$metaObject = new StandardCheckUpdateStandardClass( $this->remotePackageMetaProvider );
		return $metaObject;
	}
}
