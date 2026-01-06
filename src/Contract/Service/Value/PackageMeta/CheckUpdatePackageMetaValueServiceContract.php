<?php
/**
 * CheckUpdate Package Meta Value Service Contract
 *
 * This interface defines the contract for WordPress plugin package metadata provider factories.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Contract\Service\Value\PackageMeta
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Service\Value\PackageMeta;

use CodeKaizen\WPPackageAutoUpdater\Contract\Value\PackageMeta\CheckUpdatePackageMetaValueContract;

interface CheckUpdatePackageMetaValueServiceContract {

	/**
	 * Create a new CheckUpdatePackageMetaValue instance.
	 *
	 * @return CheckUpdatePackageMetaValueContract A new plugin package meta provider instance.
	 */
	public function getPackageMeta(): CheckUpdatePackageMetaValueContract;
}
