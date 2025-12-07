<?php
/**
 * File containing ThemeCheckInfoFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckInfo\ThemeCheckInfoMetaObject;
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
	 * @return ThemeCheckInfoMetaObject The formatted ThemeCheckInfoMetaObject containing theme information.
	 */
	public function formatForCheckInfo(): ThemeCheckInfoMetaObject {
		$stdObj = new ThemeCheckInfoMetaObject( $this->provider );
		return $stdObj;
	}
}
