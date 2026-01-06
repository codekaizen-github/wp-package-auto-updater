<?php
/**
 * File containing ThemeCheckInfoObjectFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Factory\ObjectFactoryContract;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\ThemeCheckInfoStandardClass;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\ThemePackageMetaValueServiceFactoryContract;

/**
 * ThemeCheckInfoObjectFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo
 */
class ThemeCheckInfoObjectFactory implements ObjectFactoryContract {

	/**
	 * The plugin package meta provider.
	 *
	 * @var ThemePackageMetaValueServiceFactoryContract
	 */
	private ThemePackageMetaValueServiceFactoryContract $provider;

	/**
	 * Constructor.
	 *
	 * @param ThemePackageMetaValueServiceFactoryContract $provider Description for provider.
	 *
	 * @return mixed
	 */
	public function __construct( ThemePackageMetaValueServiceFactoryContract $provider ) {
		$this->provider = $provider;
	}
	/**
	 * Format data for check info.
	 *
	 * @return ThemeCheckInfoStandardClass The formatted ThemeCheckInfoStandardClass containing plugin information.
	 */
	public function create(): ThemeCheckInfoStandardClass {
		$service     = $this->provider->create();
		$packageMeta = $service->getPackageMeta();
		$stdObj      = new ThemeCheckInfoStandardClass( $packageMeta );
		return $stdObj;
	}
}
