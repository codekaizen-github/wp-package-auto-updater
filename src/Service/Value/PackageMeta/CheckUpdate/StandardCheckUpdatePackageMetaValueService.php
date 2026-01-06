<?php
/**
 * StandardCheckUpdatePackageMetaValueService
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Service\Value\PackageMeta\CheckUpdatePackageMetaValueServiceContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Value\PackageMeta\CheckUpdatePackageMetaValueContract;
use CodeKaizen\WPPackageAutoUpdater\Exception\InvalidCheckUpdatePackageMetaException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use stdClass;
use CodeKaizen\WPPackageAutoUpdater\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValue;
use Throwable;

/**
 * Undocumented class
 */
class StandardCheckUpdatePackageMetaValueService implements CheckUpdatePackageMetaValueServiceContract {
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
	 * @return CheckUpdatePackageMetaValueContract
	 * @throws InvalidCheckUpdatePackageMetaException Throws when the package meta data is invalid.
	 */
	public function getPackageMeta(): CheckUpdatePackageMetaValueContract {
		$this->logger->debug( 'Creating StandardCheckUpdatePackageMetaValue.', [ 'fullSlug' => $this->fullSlug ] );
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
			return new StandardCheckUpdatePackageMetaValue( $item );
		} catch ( Throwable $e ) {
			$this->logger->error( 'Error creating StandardCheckUpdatePackageMetaValue.', [ 'exception' => $e ] );
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new InvalidCheckUpdatePackageMetaException( $e->getMessage(), $e->getCode(), $e );
		}
	}
}
