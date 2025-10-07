<?php

namespace CodeKaizen\WPPackageAutoUpdater\Parser\Slug;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\Parser\SlugParserContract;
use UnexpectedValueException;

/**
 * Parser for theme slugs.
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
	 */
	public function __construct( string $filePath, PackageRootContract $packageRoot ) {
		$this->filePath    = $filePath;
		$this->packageRoot = $packageRoot;
		$this->shortSlug   = null;
	}
	
	/**
	 * Get the short slug for this theme.
	 *
	 * @return string The short slug.
	 * @throws UnexpectedValueException If the slug cannot be determined.
	 */
	public function getShortSlug(): string {
		if ( null === $this->shortSlug ) {
			$themeRoot  = $this->packageRoot->getPackageRoot();
			$dirEntries = scandir( $themeRoot );

			// Get real path of the theme directory.
			$realPathFile = realpath( $this->filePath );
			if ( false === $realPathFile ) {
				throw new UnexpectedValueException( 'Could not resolve real path for file: ' . $this->filePath );
			}
			
			$realPath = realpath( dirname( $realPathFile ) );
			if ( false === $realPath ) {
				throw new UnexpectedValueException( 'Could not resolve real path for directory: ' . dirname( $realPathFile ) );
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
	public function getFullSlug(): string {
		return $this->getShortSlug() . '/style.css';
	}
}