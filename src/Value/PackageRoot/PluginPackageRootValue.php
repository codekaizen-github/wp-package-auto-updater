<?php
/**
 * File containing PluginPackageRootValue class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot
 * @subpackage PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\Contract\Value\PackageRootValueContract;

/**
 * PluginPackageRootValue class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot
 */
class PluginPackageRootValue implements PackageRootValueContract {

	/**
	 * Get the package root path.
	 *
	 * @return string
	 */
	public function getPackageRoot(): string {
		return WP_PLUGIN_DIR;
	}
}
