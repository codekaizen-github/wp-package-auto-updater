<?php
/**
 * File containing CheckInfoStrategyContract Interface class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Contract\Strategy
 * @subpackage Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Strategy;

interface CheckInfoStrategyContract {

	/**
	 * Add our self-hosted description to the filter.
	 * This method is called by WordPress when displaying the plugin/theme information
	 * in the admin UI (plugin details popup or theme details screen).
	 *
	 * @param bool|object $result  Always false initially.
	 * @param string      $action  The type of information being requested.
	 * @param object      $arg     The arguments passed to the API request.
	 * @return bool|object                  False if no action taken or object with info.
	 */
	public function checkInfo( bool|object $result, string $action, object $arg ): bool|object;
}
