<?php
/**
 * File containing CheckInfoFormatterContract Interface class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo
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
