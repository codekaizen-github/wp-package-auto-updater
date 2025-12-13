<?php
/**
 * StandardCheckUpdatePackageMetaValueServiceFactory
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\CheckUpdate;
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\CheckUpdate;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPackageAutoUpdater\Contract\Factory\Service\Value\PackageMeta\CheckUpdatePackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Accessor\MixedAccessorContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Service\Value\PackageMeta\CheckUpdatePackageMetaValueServiceContract;
use CodeKaizen\WPPackageAutoUpdater\Exception\InvalidCheckUpdatePackageMetaException;
use CodeKaizen\WPPackageAutoUpdater\Service\Value\PackageMeta\CheckUpdate\StandardCheckUpdatePackageMetaValueService;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Undocumented class
 */
class StandardCheckUpdatePackageMetaValueServiceFactory implements CheckUpdatePackageMetaValueServiceFactoryContract {
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
	 * @return CheckUpdatePackageMetaValueServiceContract
	 * @throws InvalidCheckUpdatePackageMetaException Throws when the package meta data is invalid.
	 */
	public function create(): CheckUpdatePackageMetaValueServiceContract {
		return new StandardCheckUpdatePackageMetaValueService(
			$this->accessor,
			$this->fullSlug,
			$this->logger
		);
	}
}
