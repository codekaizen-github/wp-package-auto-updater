<?php
/**
 * File containing CheckUpdateFormatterContract Interface class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate
 * @subpackage CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate;

interface CheckUpdateFormatterContract {

	/**
	 * Format data for check update.
	 *
	 * @return object The formatted object containing package information.
	 */
	public function create(): object;
}
