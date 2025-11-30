<?php
/**
 * CachingPluginPackageMetaValueService
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\Plugin
 */

namespace CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\Plugin;

use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;

/**
 * Undocumented class
 */
class CachingPluginPackageMetaValueService implements PluginPackageMetaValueServiceContract {
	/**
	 * Undocumented variable
	 *
	 * @var PluginPackageMetaValueServiceContract
	 */
	protected PluginPackageMetaValueServiceContract $service;
	/**
	 * Undocumented variable
	 *
	 * @var PluginPackageMetaValueContract|null
	 */
	protected ?PluginPackageMetaValueContract $packageMeta;
	/**
	 * Undocumented function
	 *
	 * @param PluginPackageMetaValueServiceContract $service Service.
	 */
	public function __construct( PluginPackageMetaValueServiceContract $service ) {
		$this->service     = $service;
		$this->packageMeta = null;
	}
	/**
	 * Undocumented function
	 *
	 * @return PluginPackageMetaValueContract
	 */
	public function getPackageMeta(): PluginPackageMetaValueContract {
		if ( null === $this->packageMeta ) {
			$this->packageMeta = $this->service->getPackageMeta();
		}
		return $this->packageMeta;
	}
}
