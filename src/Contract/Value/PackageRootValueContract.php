<?php
/**
 * File containing PackageRootValueContract Interface class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Contract\Value
 * @subpackage PackageRoot
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Value;

interface PackageRootValueContract {

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
