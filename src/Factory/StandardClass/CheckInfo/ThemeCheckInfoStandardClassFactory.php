<?php
/**
 * File containing ThemeCheckInfoStandardClassFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckInfo
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\ThemeCheckInfoStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;

/**
 * ThemeCheckInfoStandardClassFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckInfo
 */
class ThemeCheckInfoStandardClassFactory implements CheckInfoFormatterContract {

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
