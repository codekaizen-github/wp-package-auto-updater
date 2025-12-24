<?php
/**
 * File containing CheckUpdateHookContract Interface class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Contract\Hook
 * @subpackage Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Hook;

use stdClass;

interface CheckUpdateHookContract {

	/**
	 * Check for updates.
	 *
	 * @param stdClass $transient The transient object containing update data.
	 *
	 * @return stdClass The modified transient object with update information.
	 */
	public function checkUpdate( stdClass $transient ): stdClass;
}
