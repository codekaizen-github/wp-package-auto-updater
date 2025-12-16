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
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;

/**
 * ThemeCheckInfoObjectFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo
 */
class ThemeCheckInfoObjectFactory implements ObjectFactoryContract {

	/**
	 * The theme package meta provider.
	 *
	 * @var PackageMetaValueContract
	 */
	private PackageMetaValueContract $provider;

	/**
	 * Constructor.
	 *
	 * @param PackageMetaValueContract $provider Description for provider.
	 *
	 * @return mixed
	 */
	public function __construct( PackageMetaValueContract $provider ) {
		$this->provider = $provider;
	}
	/**
	 * Format data for check info.
	 *
	 * @return ThemeCheckInfoStandardClass The formatted ThemeCheckInfoStandardClass containing theme information.
	 */
	public function create(): ThemeCheckInfoStandardClass {
		$stdObj = new ThemeCheckInfoStandardClass( $this->provider );
		return $stdObj;
	}
}
