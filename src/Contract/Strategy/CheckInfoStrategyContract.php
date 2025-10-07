<?php
/**
 * File containing CheckInfoStrategyContract Interface class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Strategy;

interface CheckInfoStrategyContract {

	/**
	 * Add our self-hosted description to the filter.
	 * This method is called by WordPress when displaying the plugin/theme information
	 * in the admin UI (plugin details popup or theme details screen).
	 *
	 * @param bool                  $false  Always false initially.
	 * @param array<string, string> $action The type of information being requested.
	 * @param object                $arg    The arguments passed to the API request.
	 * @return bool|object                  False if no action taken or object with info.
	 */
	/**
	 * Check Info.
	 *
	 * @param bool $false Description for false.
	 * @param array $action Description for action.
	 * @param object $arg Description for arg.
	 *
	 * @return void
	 */
	/**
	 * Check Info.
	 *
	 * @param bool                  $result Always false initially.
	 * @param array<string, string> $action The type of information being requested.
	 * @param object                $arg The arguments passed to the API request.
	 *
	 * @return bool|object False if no action taken or object with info.
	 */
	public function checkInfo( bool $result, array $action, object $arg ): bool|object;
}
