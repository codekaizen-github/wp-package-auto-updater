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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
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
	 * Undocumented variable
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;
	/**
	 * Undocumented function
	 *
	 * @param MixedAccessorContract $accessor Accessor.
	 * @param string                $fullSlug FullSlug.
	 * @param LoggerInterface       $logger   Logger.
	 */
	public function __construct(
		MixedAccessorContract $accessor,
		string $fullSlug,
		LoggerInterface $logger = new NullLogger()
	) {
		$this->accessor = $accessor;
		$this->fullSlug = $fullSlug;
		$this->logger   = $logger;
	}
	/**
	 * Undocumented function
	 *
	 * @return CheckUpdatePackageMetaProviderContract
	 * @throws InvalidCheckUpdatePackageMetaException Throws when the package meta data is invalid.
	 */
	/**
	 * Undocumented function
	 *
	 * @return CheckUpdatePackageMetaProviderContract
	 * @throws InvalidCheckUpdatePackageMetaException Throws when the package meta data is invalid.
	 */
	public function create(): CheckUpdatePackageMetaProviderContract {
		$this->logger->info( 'Creating CheckUpdatePackageMetaProvider.', [ 'fullSlug' => $this->fullSlug ] );
		// Fetch the transient.
		$transient = $this->accessor->get();
		if ( ! is_object( $transient ) ) {
			$this->logger->error( 'Transient is not an object', [ 'transient' => $transient ] );
			throw new InvalidCheckUpdatePackageMetaException( 'Transient is not an object' );
		}
		if ( ! isset( $transient->response ) ) {
			$this->logger->error( 'Transient response is not set.', [ 'transient' => $transient ] );
			throw new InvalidCheckUpdatePackageMetaException( 'Transient response is not set.' );
		}
		$transientResponse = $transient->response;
		// - Confirm it is an array
		if ( ! is_array( $transientResponse ) ) {
			$this->logger->error(
				'Transient response is not an array.',
				[ 'transientResponse' => $transientResponse ]
			);
			throw new InvalidCheckUpdatePackageMetaException( 'Transient response is not an array.' );
		}
		/**
		 * Validated.
		 *
		 * @var array<string,stdClass> $transientResponse.
		 */
		// - Search for the item with the matching key
		$item = $transientResponse[ $this->fullSlug ] ?? null;
		// - If not found, return null
		if ( ! is_object( $item ) ) {
			$this->logger->error( 'Transient response item is not an object.', [ 'item' => $item ] );
			throw new InvalidCheckUpdatePackageMetaException( 'Transient response item is not an object.' );
		}
		// - If found, instantiate and return Contract, passing in data
		try {
			return new CheckUpdatePackageMetaProvider( $item );
		} catch ( Throwable $e ) {
			$this->logger->error( 'Error creating CheckUpdatePackageMetaProvider.', [ 'exception' => $e ] );
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new InvalidCheckUpdatePackageMetaException( $e->getMessage(), $e->getCode(), $e );
		}
	}
}
