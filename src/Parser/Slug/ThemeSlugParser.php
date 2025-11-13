<?php
/**
 * File containing ThemeSlugParser class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Parser\Slug
 * @subpackage Slug
 */

namespace CodeKaizen\WPPackageAutoUpdater\Parser\Slug;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\Parser\SlugParserContract;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use UnexpectedValueException;

/**
 * Parser for theme slugs.
 */
/**
 * ThemeSlugParser class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Parser\Slug
 */
class ThemeSlugParser implements SlugParserContract {

	/**
	 * The package root contract.
	 *
	 * @var PackageRootContract
	 */
	protected PackageRootContract $packageRoot;

	/**
	 * The file path.
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
	 * The short slug.
	 *
	 * @var string|null
	 */
	protected ?string $shortSlug;

	/**
	 * Constructor.
	 *
	 * @param string              $filePath    The file path.
	 * @param PackageRootContract $packageRoot The package root contract.
	 * @param LoggerInterface     $logger      The logger instance.
	 */
	/**
	 * Constructor.
	 *
	 * @param string              $filePath Description for filePath.
	 * @param PackageRootContract $packageRoot Description for packageRoot.
	 * @param LoggerInterface     $logger Description for logger.
	 *
	 * @return mixed
	 */
	public function __construct(
		string $filePath,
		PackageRootContract $packageRoot,
		LoggerInterface $logger = new NullLogger()
	) {
		$this->filePath    = $filePath;
		$this->packageRoot = $packageRoot;
		$this->logger      = $logger;
		$this->shortSlug   = null;
	}

	/**
	 * Get the short slug for this theme.
	 *
	 * @return string The short slug.
	 * @throws UnexpectedValueException If the slug cannot be determined.
	 */
	/**
	 * Get Short Slug.
	 *
	 * @return string The short slug for this theme.
	 * @throws \UnexpectedValueException If the slug cannot be determined.
	 */
	public function getShortSlug(): string {
		if ( null === $this->shortSlug ) {
			$themeRoot  = $this->packageRoot->getPackageRoot();
			$dirEntries = scandir( $themeRoot );

			// Get real path of the theme directory.
			$realPathFile = realpath( $this->filePath );
			if ( false === $realPathFile ) {
				$errorMsg = 'Could not resolve real path for file: ' . $this->filePath;
				$this->logger->error( $errorMsg );
				// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
				throw new UnexpectedValueException( $errorMsg );
			}

			$realPath = realpath( dirname( $realPathFile ) );
			if ( false === $realPath ) {
				$dirPath  = dirname( $realPathFile );
				$errorMsg = 'Could not resolve real path for directory: ' . $dirPath;
				$this->logger->error( $errorMsg );
				// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
				throw new UnexpectedValueException( $errorMsg );
			}

			$slug = null;
			// Try to map back to symlink entry.
			foreach ( $dirEntries as $entry ) {
				if ( '.' === $entry || '..' === $entry ) {
					continue;
				}
				$candidate = $themeRoot . '/' . $entry;

				if ( realpath( $candidate ) === $realPath ) {
					$slug = $entry;
					break;
				}
			}
			if ( ! is_string( $slug ) ) {
				$this->logger->error( 'Expected slug to be string. Got ' . gettype( $slug ) . ' instead.' );
				// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
				throw new UnexpectedValueException( "Expected slug to be string. Got $slug instead." );
			}
			$this->shortSlug = $slug;
		}
		return $this->shortSlug;
	}

	/**
	 * Get the full slug for this theme.
	 *
	 * @return string The full slug.
	 */
	/**
	 * Get Full Slug.
	 *
	 * @return string The full slug for this theme.
	 */
	public function getFullSlug(): string {
		return $this->getShortSlug() . '/style.css';
	}
}
