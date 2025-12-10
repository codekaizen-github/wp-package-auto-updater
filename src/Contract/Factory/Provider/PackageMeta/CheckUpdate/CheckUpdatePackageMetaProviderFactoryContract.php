<?php
/**
 * CheckUpdatePackageMetaProviderFactory
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Contract\Factory\Provider\PackageMeta\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Factory\Provider\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Value\PackageMeta\CheckUpdatePackageMetaValueContract;

interface CheckUpdatePackageMetaProviderFactoryContract {
	/**
	 * Create a CheckUpdatePackageMetaProvider instance.
	 */
	public function create(): CheckUpdatePackageMetaValueContract;
}
