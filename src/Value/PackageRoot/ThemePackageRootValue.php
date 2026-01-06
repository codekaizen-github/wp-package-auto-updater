<?php
/**
 * File containing ThemePackageRootValue class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot
 * @subpackage PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\Contract\Value\PackageRootValueContract;

/**
 * ThemePackageRootValue class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot
 */
class ThemePackageRootValue implements PackageRootValueContract {

	/**
	 * Get the package root path.
	 *
	 * @return string
	 */
	public function getPackageRoot(): string {
		return get_theme_root();
	}
}
