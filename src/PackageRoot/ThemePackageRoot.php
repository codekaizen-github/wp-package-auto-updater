<?php
/**
 * File containing ThemePackageRoot class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\PackageRoot;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;

/**
 * ThemePackageRoot class.
 *
 * @package WPPackageAutoUpdater
 */


class ThemePackageRoot implements PackageRootContract {

	/**
	 * Get the package root path.
	 *
	 * @return string
	 */
	public function getPackageRoot(): string {
		return get_theme_root();
	}
}
