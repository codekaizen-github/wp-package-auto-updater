<?php
/**
 * CachingThemePackageMetaValueService
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\Theme
 */

namespace CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\Theme;

use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\ThemePackageMetaValueContract;

/**
 * Undocumented class
 */
class CachingThemePackageMetaValueService implements ThemePackageMetaValueServiceContract {
	/**
	 * Undocumented variable
	 *
	 * @var ThemePackageMetaValueServiceContract
	 */
	protected ThemePackageMetaValueServiceContract $service;
	/**
	 * Undocumented variable
	 *
	 * @var ThemePackageMetaValueContract|null
	 */
	protected ?ThemePackageMetaValueContract $packageMeta;
	/**
	 * Undocumented function
	 *
	 * @param ThemePackageMetaValueServiceContract $service Service.
	 */
	public function __construct( ThemePackageMetaValueServiceContract $service ) {
		$this->service     = $service;
		$this->packageMeta = null;
	}
	/**
	 * Undocumented function
	 *
	 * @return ThemePackageMetaValueContract
	 */
	public function getPackageMeta(): ThemePackageMetaValueContract {
		if ( null === $this->packageMeta ) {
			$this->packageMeta = $this->service->getPackageMeta();
		}
		return $this->packageMeta;
	}
}
