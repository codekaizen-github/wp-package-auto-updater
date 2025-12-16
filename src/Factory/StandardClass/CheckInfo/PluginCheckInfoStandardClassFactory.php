<?php
/**
 * File containing PluginCheckInfoStandardClassFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckInfo
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\PluginCheckInfoStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;

/**
 * PluginCheckInfoStandardClassFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckInfo
 */
class PluginCheckInfoStandardClassFactory implements CheckInfoFormatterContract {

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
