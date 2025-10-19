<?php
/**
 * File containing PluginCheckInfoFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckInfo\PluginCheckInfoMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PluginPackageMetaProviderContract;

/**
 * PluginCheckInfoFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo
 */
class PluginCheckInfoFormatter implements CheckInfoFormatterContract {

	/**
	 * The plugin package meta provider.
	 *
	 * @var PluginPackageMetaProviderContract
	 */
	private PluginPackageMetaProviderContract $provider;

	/**
	 * Constructor.
	 *
	 * @param PluginPackageMetaProviderContract $provider Description for provider.
	 *
	 * @return mixed
	 */
	public function __construct( PluginPackageMetaProviderContract $provider ) {
		$this->provider = $provider;
	}
	/**
	 * Format data for check info.
	 *
	 * @return PluginCheckInfoMetaObject The formatted PluginCheckInfoMetaObject containing plugin information.
	 */
	public function formatForCheckInfo(): PluginCheckInfoMetaObject {
		$stdObj = new PluginCheckInfoMetaObject( $this->provider );
		return $stdObj;
	}
}
