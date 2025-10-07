<?php
/**
 * File containing CheckInfoFormatterContract Interface class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo;

interface CheckInfoFormatterContract {

	/**
	 * Format data for check info.
	 *
	 * @return object The formatted object containing package information.
	 */
	public function formatForCheckInfo(): object;
}
