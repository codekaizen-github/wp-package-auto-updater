<?php
/**
 * File containing PluginPackageRoot class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\PackageRoot
 * @subpackage PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\Contract\Value\PackageRootValueContract;

/**
 * PluginPackageRoot class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\PackageRoot
 */
class PluginPackageRoot implements PackageRootValueContract {

	/**
	 * Get the package root path.
	 *
	 * @return string
	 */
	public function getPackageRoot(): string {
		return WP_PLUGIN_DIR;
	}
}
