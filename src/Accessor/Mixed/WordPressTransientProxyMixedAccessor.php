<?php
/**
 * Transient WordPress Provider.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Accessor\Mixed
 */

namespace CodeKaizen\WPPackageAutoUpdater\Accessor\Mixed;

use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;

/**
 * Undocumented class
 */
class WordPressTransientProxyMixedAccessor implements MixedAccessorContract {
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	private string $key;
	/**
	 * Undocumented function
	 *
	 * @param string $key Key.
	 */
	public function __construct( string $key ) {
		$this->key = $key;
	}
	/**
	 * Undocumented function
	 *
	 * @return mixed
	 */
	public function get(): mixed {
		return get_site_transient( $this->key );
	}
}
