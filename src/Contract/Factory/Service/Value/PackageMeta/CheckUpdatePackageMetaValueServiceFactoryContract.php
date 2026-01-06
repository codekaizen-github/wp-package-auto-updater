<?php
/**
 * File containing CheckUpdatePackageMetaValueServiceFactoryContract interface.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Contract\Factory\Service\Value\PackageMeta
 * @subpackage Factory
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Factory\Service\Value\PackageMeta;

use CodeKaizen\WPPackageAutoUpdater\Contract\Service\Value\PackageMeta\CheckUpdatePackageMetaValueServiceContract;

interface CheckUpdatePackageMetaValueServiceFactoryContract {
	/**
	 * Create a new instance.
	 *
	 * @return CheckUpdatePackageMetaValueServiceContract The created plugin package meta value service.
	 */
	public function create(): CheckUpdatePackageMetaValueServiceContract;
}
