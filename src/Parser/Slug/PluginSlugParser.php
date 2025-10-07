<?php

namespace CodeKaizen\WPPackageAutoUpdater\Parser\Slug;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\Parser\SlugParserContract;
use UnexpectedValueException;

/**
 * Parser for plugin slugs.
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
	public function getShortSlug(): string {
		if ( null === $this->shortSlug ) {
			$split           = explode( '/', $this->getFullSlug() );
			$this->shortSlug = (string) array_shift( $split );
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
	public function getFullSlug(): string {
		if ( null === $this->fullSlug ) {
			$pluginRoot = $this->packageRoot->getPackageRoot();
			$dirEntries = scandir( $pluginRoot );

			$realPathFile = realpath( $this->filePath );
			if ( false === $realPathFile ) {
				throw new UnexpectedValueException( 'Could not resolve real path for file: ' . $this->filePath );
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
				throw new UnexpectedValueException( "Expected slug to be string. Got $slug instead." );
			}
			$this->fullSlug = $slug;
		}
		return $this->fullSlug;
	}
}
