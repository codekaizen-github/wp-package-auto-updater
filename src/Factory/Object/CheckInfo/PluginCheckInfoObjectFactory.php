<?php
/**
 * File containing PluginCheckInfoObjectFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Factory\ObjectFactoryContract;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\PluginCheckInfoStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;

/**
 * PluginCheckInfoObjectFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo
 */
class PluginCheckInfoObjectFactory implements ObjectFactoryContract {

	/**
	 * The plugin package meta provider.
	 *
	 * @var PluginPackageMetaValueContract
	 */
	private PluginPackageMetaValueContract $provider;

	/**
	 * Constructor.
	 *
	 * @param PluginPackageMetaValueContract $provider Description for provider.
	 *
	 * @return mixed
	 */
	public function __construct( PluginPackageMetaValueContract $provider ) {
		$this->provider = $provider;
	}
	/**
	 * Format data for check info.
	 *
	 * @return PluginCheckInfoStandardClass The formatted PluginCheckInfoStandardClass containing plugin information.
	 */
	public function create(): PluginCheckInfoStandardClass {
		$stdObj = new PluginCheckInfoStandardClass( $this->provider );
		return $stdObj;
	}
}
