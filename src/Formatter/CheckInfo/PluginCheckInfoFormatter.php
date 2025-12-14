<?php
/**
 * File containing PluginCheckInfoFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\PluginCheckInfoStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PluginPackageMetaValueContract;

/**
 * PluginCheckInfoFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo
 */
class PluginCheckInfoFormatter implements CheckInfoFormatterContract {

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
	public function formatForCheckInfo(): PluginCheckInfoStandardClass {
		$stdObj = new PluginCheckInfoStandardClass( $this->provider );
		return $stdObj;
	}
}
