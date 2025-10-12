<?php
/**
 * File containing PluginPackageRoot class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\PackageRoot
 * @subpackage PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;

/**
 * PluginPackageRoot class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\PackageRoot
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
