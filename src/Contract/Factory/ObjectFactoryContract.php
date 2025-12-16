<?php
/**
 * File containing ObjectFactoryContract.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Contract\Factory
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Factory;

interface ObjectFactoryContract {

	/**
	 * Format data for check info.
	 *
	 * @return object The formatted object containing package information.
	 */
	public function create(): object;
}
