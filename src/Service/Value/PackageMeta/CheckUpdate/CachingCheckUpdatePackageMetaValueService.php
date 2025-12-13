<?php
/**
 * CachingCheckUpdatePackageMetaValueService
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Service\Value\PackageMeta\CheckUpdatePackageMetaValueServiceContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Value\PackageMeta\CheckUpdatePackageMetaValueContract;

/**
 * Undocumented class
 */
class CachingCheckUpdatePackageMetaValueService implements CheckUpdatePackageMetaValueServiceContract {
	/**
	 * Undocumented variable
	 *
	 * @var CheckUpdatePackageMetaValueServiceContract
	 */
	protected CheckUpdatePackageMetaValueServiceContract $service;
	/**
	 * Undocumented variable
	 *
	 * @var CheckUpdatePackageMetaValueContract|null
	 */
	protected ?CheckUpdatePackageMetaValueContract $packageMeta;
	/**
	 * Undocumented function
	 *
	 * @param CheckUpdatePackageMetaValueServiceContract $service Service.
	 */
	public function __construct( CheckUpdatePackageMetaValueServiceContract $service ) {
		$this->service     = $service;
		$this->packageMeta = null;
	}
	/**
	 * Undocumented function
	 *
	 * @return CheckUpdatePackageMetaValueContract
	 */
	public function getPackageMeta(): CheckUpdatePackageMetaValueContract {
		if ( null === $this->packageMeta ) {
			$this->packageMeta = $this->service->getPackageMeta();
		}
		return $this->packageMeta;
	}
}
