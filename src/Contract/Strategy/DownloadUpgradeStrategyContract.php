<?php
/**
 * File containing DownloadUpgradeStrategyContract interface.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Contract\Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Strategy;

/**
 * Interface for download upgrade strategy.
 */
interface DownloadUpgradeStrategyContract {

	/**
	 * Downloads an upgrade package if appropriate.
	 *
	 * @param bool|string  $reply       Whether to bail without returning the package or path to downloaded file.
	 * @param string       $package     The package file name.
	 * @param mixed        $upgrader    The WP_Upgrader instance.
	 * @param array<mixed> $hookExtra   Extra arguments passed to hooked filters.
	 *
	 * @return bool|string False to use default upgrade process, or path to downloaded file.
	 */
	public function downloadUpgrade( $reply, string $package, $upgrader, array $hookExtra ): bool|string;
}
