<?php
/**
 * File containing ThemeCheckInfoFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckInfo\ThemeCheckInfoStandardClass;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;

/**
 * ThemeCheckInfoFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo
 */
class ThemeCheckInfoFormatter implements CheckInfoFormatterContract {

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
	public function formatForCheckInfo(): ThemeCheckInfoStandardClass {
		$stdObj = new ThemeCheckInfoStandardClass( $this->provider );
		return $stdObj;
	}
}
