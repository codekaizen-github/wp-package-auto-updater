<?php
/**
 * File containing LocalThemePackageMetaProviderFactory class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage Local
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Theme;

use CodeKaizen\WPPackageAutoUpdater\PackageRoot\ThemePackageRoot;
use CodeKaizen\WPPackageAutoUpdater\Parser\Slug\ThemeSlugParser;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1 as LocalThemePackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;

/**
 * LocalThemePackageMetaProviderFactory class.
 *
 * @package WPPackageAutoUpdater
 */
class LocalThemePackageMetaProviderFactory implements ThemePackageMetaProviderFactoryContract {

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
	 * The theme package meta provider instance.
	 *
	 * @var ThemePackageMetaContract|null
	 */
	protected ?ThemePackageMetaContract $provider;
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
	 * @return ThemePackageMetaContract The created theme package meta provider.
	 */
	public function create(): ThemePackageMetaContract {
		if ( null === $this->provider ) {
			$themeSlugParser = new ThemeSlugParser(
				$this->filePath,
				new ThemePackageRoot()
			);

			$factory = new LocalThemePackageMetaProviderFactoryV1(
				$this->filePath,
				$themeSlugParser,
				$this->logger
			);

			$this->provider = $factory->create();
		}
		return $this->provider;
	}
}
