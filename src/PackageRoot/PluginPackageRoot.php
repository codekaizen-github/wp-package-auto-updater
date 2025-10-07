<?php
/**
 * File containing PluginPackageRoot class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;

/**
 * PluginPackageRoot class.
 *
 * @package WPPackageAutoUpdater
 */


class PluginPackageRoot implements PackageRootContract {

	/**
	 * Get the package root path.
	 *
	 * @return string
	 */
	public function getPackageRoot(): string {
		return WP_PLUGIN_DIR;
	}
}
