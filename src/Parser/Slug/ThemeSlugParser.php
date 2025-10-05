<?php

namespace CodeKaizen\WPPackageAutoUpdater\Parser\Slug;

use CodeKaizen\WPPackageAutoUpdater\Contract\PackageRoot\PackageRootContract;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\Parser\SlugParserContract;
use UnexpectedValueException;

class ThemeSlugParser implements SlugParserContract {

	protected PackageRootContract $packageRoot;
	protected string $filePath;
	protected ?string $shortSlug;
	public function __construct( string $filePath, PackageRootContract $packageRoot ) {
		$this->filePath    = $filePath;
		$this->packageRoot = $packageRoot;
		$this->shortSlug   = null;
	}
	public function getShortSlug(): string {
		if ( null === $this->shortSlug ) {
			$theme_root  = $this->packageRoot->getPackageRoot();
			$dir_entries = scandir( $theme_root );

			// your real path
			$real_path = realpath( dirname( realpath( $this->filePath ) ) );
			$slug      = null;
			// try to map back to symlink entry
			foreach ( $dir_entries as $entry ) {
				if ( $entry === '.' || $entry === '..' ) {
					continue;
				}
				$candidate = $theme_root . '/' . $entry;

				if ( realpath( $candidate ) === $real_path ) {
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
	public function getFullSlug(): string {
		return $this->getShortSlug() . '/style.css';
	}
}
