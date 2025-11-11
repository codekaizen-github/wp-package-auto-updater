<?php
/**
 * CheckUpdatePackageMetaProviderFactory
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\CheckUpdate;
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Contract\Factory\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageAutoUpdater\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProvider;
use stdClass;
use CodeKaizen\WPPackageAutoUpdater\Contract\Provider\PackageMeta\CheckUpdate\CheckUpdatePackageMetaProviderContract;
use CodeKaizen\WPPackageAutoUpdater\Exception\InvalidCheckUpdatePackageMetaException;
use Throwable;

/**
 * Undocumented class
 */
class CheckUpdatePackageMetaProviderFactory implements CheckUpdatePackageMetaProviderFactoryContract {
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
	 * @return CheckUpdatePackageMetaProviderContract
	 * @throws InvalidCheckUpdatePackageMetaException Throws when the package meta data is invalid.
	 */
	public function create(): CheckUpdatePackageMetaProviderContract {
		// - Fetch the transient
		$transient = $this->accessor->get();
		// - Confirm it is an array
		if ( ! is_array( $transient ) ) {
			throw new InvalidCheckUpdatePackageMetaException();
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
			throw new InvalidCheckUpdatePackageMetaException();
		}
		// - If found, instantiate and return Contract, passing in data
		try {
			return new CheckUpdatePackageMetaProvider( $item );
		} catch ( Throwable $e ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new InvalidCheckUpdatePackageMetaException( $e->getMessage(), $e->getCode(), $e );
		}
	}
}
