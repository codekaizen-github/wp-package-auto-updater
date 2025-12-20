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
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryContract;

/**
 * PluginCheckInfoObjectFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo
 */
class PluginCheckInfoObjectFactory implements ObjectFactoryContract {

	/**
	 * The plugin package meta provider.
	 *
	 * @var PluginPackageMetaValueServiceFactoryContract
	 */
	private PluginPackageMetaValueServiceFactoryContract $provider;

	/**
	 * Constructor.
	 *
	 * @param PluginPackageMetaValueServiceFactoryContract $provider Description for provider.
	 *
	 * @return mixed
	 */
	public function __construct( PluginPackageMetaValueServiceFactoryContract $provider ) {
		$this->provider = $provider;
	}
	/**
	 * Format data for check info.
	 *
	 * @return PluginCheckInfoStandardClass The formatted PluginCheckInfoStandardClass containing plugin information.
	 */
	public function create(): PluginCheckInfoStandardClass {
		$service     = $this->provider->create();
		$packageMeta = $service->getPackageMeta();
		$stdObj      = new PluginCheckInfoStandardClass( $packageMeta );
		return $stdObj;
	}
}
