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
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PackageMetaProviderContract;

/**
 * ThemeCheckInfoFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo
 */
class ThemeCheckInfoFormatter implements CheckInfoFormatterContract {

	/**
	 * The theme package meta provider.
	 *
	 * @var PackageMetaProviderContract
	 */
	private PackageMetaProviderContract $provider;

	/**
	 * Constructor.
	 *
	 * @param PackageMetaProviderContract $provider Description for provider.
	 *
	 * @return mixed
	 */
	public function __construct( PackageMetaProviderContract $provider ) {
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
