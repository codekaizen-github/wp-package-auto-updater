<?php

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Local;

use CodeKaizen\WPPackageAutoUpdater\PackageRoot\ThemePackageRoot;
use CodeKaizen\WPPackageAutoUpdater\Parser\Slug\ThemeSlugParser;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1 as LocalThemePackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;

class LocalThemePackageMetaProviderFactory implements ThemePackageMetaProviderFactoryContract {

	protected string $filePath;
	protected LoggerInterface $logger;
	protected ?ThemePackageMetaContract $provider;
	public function __construct( string $filePath, LoggerInterface $logger ) {
		$this->filePath = $filePath;
		$this->logger   = $logger;
	}
	public function create(): ThemePackageMetaContract {
		if ( null === $this->provider ) {
			$factory        = new LocalThemePackageMetaProviderFactoryV1( $this->filePath, new ThemeSlugParser( $this->filePath, new ThemePackageRoot() ), $this->logger );
			$this->provider = $factory->create();
		}
		return $this->provider;
	}
}
