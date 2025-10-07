<?php
/**
 * File containing CheckUpdateStrategyContract Interface class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Strategy;

use stdClass;

interface CheckUpdateStrategyContract {

	/**
	 * Check for updates.
	 *
	 * @param stdClass $transient The transient object containing update data.
	 *
	 * @return stdClass The modified transient object with update information.
	 */
	public function checkUpdate( stdClass $transient ): stdClass;
}
