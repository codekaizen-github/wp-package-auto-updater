<?php
/**
 * File containing PackageRootContract Interface class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot
 * @subpackage PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot;

interface PackageRootContract {

	/**
	 * Get the package root path.
	 *
	 * @return string
	 */
	/**
	 * Get the package root path.
	 *
	 * @return string
	 */
	public function getPackageRoot(): string;
}
