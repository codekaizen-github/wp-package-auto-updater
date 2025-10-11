<?php
/**
 * File containing ThemeCheckInfoFormatter class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckInfo\ThemeCheckInfoMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use stdClass;

/**
 * ThemeCheckInfoFormatter class.
 *
 * @package WPPackageAutoUpdater
 */
class ThemeCheckInfoFormatter implements CheckInfoFormatterContract {

	/**
	 * The theme package meta provider.
	 *
	 * @var PackageMetaContract
	 */
	private PackageMetaContract $provider;

	/**
	 * Constructor.
	 *
	 * @param PackageMetaContract $provider Description for provider.
	 *
	 * @return mixed
	 */
	public function __construct( PackageMetaContract $provider ) {
		$this->provider = $provider;
	}
	/**
	 * Format data for check info.
	 *
	 * @return object The formatted object containing theme information.
	 */
	public function formatForCheckInfo(): object {
		$stdObj = new ThemeCheckInfoMetaObject( $this->provider );
		return $stdObj;
	}
}
