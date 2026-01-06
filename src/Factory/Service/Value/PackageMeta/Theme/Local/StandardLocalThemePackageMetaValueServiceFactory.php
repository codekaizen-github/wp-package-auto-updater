<?php
/**
 * File containing StandardLocalThemePackageMetaValueServiceFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Theme\Local
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Theme\Local;

use CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot\ThemePackageRootValue;
use CodeKaizen\WPPackageAutoUpdater\Value\Slug\ThemeSlugValue;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\ThemePackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Service\Value\PackageMeta\Theme\StandardThemePackageMetaValueServiceFactory;
use Psr\Log\LoggerInterface;

/**
 * StandardLocalThemePackageMetaValueServiceFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Theme\Local
 */
class StandardLocalThemePackageMetaValueServiceFactory implements ThemePackageMetaValueServiceFactoryContract {

	/**
	 * The file path for the theme package.
	 *
	 * @var string
	 */
	protected string $filePath;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param string          $filePath Description for filePath.
	 * @param LoggerInterface $logger Description for logger.
	 */
	public function __construct( string $filePath, LoggerInterface $logger ) {
		$this->filePath = $filePath;
		$this->logger   = $logger;
	}
	/**
	 * Create a new instance.
	 *
	 * @return ThemePackageMetaValueServiceContract The created theme package meta provider.
	 */
	public function create(): ThemePackageMetaValueServiceContract {
		$this->logger->debug( 'Creating new theme package meta provider.' );
		$themeSlugValue = new ThemeSlugValue(
			$this->filePath,
			new ThemePackageRootValue(),
			$this->logger
		);

		$factory = new StandardThemePackageMetaValueServiceFactory(
			$this->filePath,
			$themeSlugValue,
			$this->logger
		);

		return $factory->create();
	}
}
