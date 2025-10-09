<?php
/**
 * File containing PluginSlugParser class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage Slug
 */

namespace CodeKaizen\WPPackageAutoUpdater\Parser\Slug;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\Parser\SlugParserContract;
use UnexpectedValueException;

/**
 * Parser for plugin slugs.
 */
/**
 * PluginSlugParser class.
 *
 * @package WPPackageAutoUpdater
 */
class PluginSlugParser implements SlugParserContract {

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
	 * The short slug.
	 *
	 * @var string|null
	 */
	protected ?string $shortSlug;

	/**
	 * The full slug.
	 *
	 * @var string|null
	 */
	protected ?string $fullSlug;

	/**
	 * Constructor.
	 *
	 * @param string              $filePath    The file path.
	 * @param PackageRootContract $packageRoot The package root contract.
	 */
	/**
	 * Constructor.
	 *
	 * @param string              $filePath Description for filePath.
	 * @param PackageRootContract $packageRoot Description for packageRoot.
	 *
	 * @return mixed
	 */
	public function __construct( string $filePath, PackageRootContract $packageRoot ) {
		$this->filePath    = $filePath;
		$this->packageRoot = $packageRoot;
		$this->shortSlug   = null;
		$this->fullSlug    = null;
	}

	/**
	 * Get the short slug for this plugin.
	 *
	 * @return string The short slug.
	 * @throws UnexpectedValueException If the slug cannot be determined.
	 */
	/**
	 * Get Short Slug.
	 *
	 * @return string The short slug for this plugin.
	 * @throws \UnexpectedValueException If the slug cannot be determined.
	 */
	public function getShortSlug(): string {
		if ( null === $this->shortSlug ) {
			$split             = explode( '/', $this->getFullSlug() );
			$firstFilePathPart = (string) array_shift( $split );
			$this->shortSlug   = basename( $firstFilePathPart, '.php' );
		}

		// Explicitly ensure the return value is a string.
		if ( '' === $this->shortSlug ) {
			throw new UnexpectedValueException( 'Failed to determine short slug.' );
		}

		return $this->shortSlug;
	}

	/**
	 * Get the full slug for this plugin.
	 *
	 * @return string The full slug.
	 * @throws UnexpectedValueException If the slug cannot be determined.
	 */
	/**
	 * Get Full Slug.
	 *
	 * @return string The full slug for this plugin.
	 * @throws \UnexpectedValueException If the slug cannot be determined.
	 */
	public function getFullSlug(): string {
		if ( null === $this->fullSlug ) {
			$pluginRoot = $this->packageRoot->getPackageRoot();
			$dirEntries = scandir( $pluginRoot );

			$realPathFile = realpath( $this->filePath );
			echo $realPathFile;
			if ( false === $realPathFile ) {
				$errorMsg = 'Could not resolve real path for file: ' . $this->filePath;
				// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
				throw new UnexpectedValueException( $errorMsg );
			}

			$realPathFileBasename = basename( $realPathFile );
			// Resolve the real path of this plugin's directory.
			$realPathDir = dirname( $realPathFile );

			$slug = null;

			foreach ( $dirEntries as $entry ) {
				if ( '.' === $entry || '..' === $entry ) {
					continue;
				}

				$candidate = $pluginRoot . '/' . $entry;

				// Check directory plugins.
				if ( is_dir( $candidate ) ) {
					if ( realpath( $candidate ) === $realPathDir ) {
						$slug = $entry . '/' . $realPathFileBasename;
						break;
					}
				}

				// Check single-file plugins.
				if ( is_file( $candidate ) ) {
					if ( realpath( $candidate ) === $realPathFile ) {
						$slug = $realPathFileBasename;
						break;
					}
				}
			}
			if ( ! is_string( $slug ) ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
				throw new UnexpectedValueException( "Expected slug to be string. Got $slug instead." );
			}
			$this->fullSlug = $slug;
		}
		return $this->fullSlug;
	}
}
