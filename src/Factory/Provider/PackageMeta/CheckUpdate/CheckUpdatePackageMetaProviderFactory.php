<?php
/**
 * CheckUpdatePackageMetaProviderFactory
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\CheckUpdate;
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;
use CodeKaizen\WPPackageAutoUpdater\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProvider;
use stdClass;
use CodeKaizen\WPPackageAutoUpdater\Contract\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProviderContract;

/**
 * Undocumented class
 */
class CheckUpdatePackageMetaProviderFactory {
	/**
	 * Undocumented variable
	 *
	 * @var MixedAccessorContract
	 */
	protected MixedAccessorContract $accessor;
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected string $fullSlug;
	/**
	 * Undocumented function
	 *
	 * @param MixedAccessorContract $accessor Accessor.
	 * @param string                $fullSlug FullSlug.
	 */
	public function __construct( MixedAccessorContract $accessor, string $fullSlug ) {
		$this->accessor = $accessor;
		$this->fullSlug = $fullSlug;
	}
	/**
	 * Undocumented function
	 *
	 * @return ?CheckUpdatePackageMetaProviderContract
	 */
	public function create() {
		// - Fetch the transient
		$transient = $this->accessor->get();
		// - Confirm it is an array
		if ( ! is_array( $transient ) ) {
			// - If not, return null
			return null;
		}
		/**
		 * Validated.
		 *
		 * @var array<string,stdClass> $transient.
		 */
		// - Search for the item with the matching key
		$item = $transient[ $this->fullSlug ] ?? null;
		// - If not found, return null
		if ( ! is_object( $item ) ) {
			return null;
		}
		// - If found, instantiate and return Contract, passing in data
		return new CheckUpdatePackageMetaProvider( $item );
	}
}
