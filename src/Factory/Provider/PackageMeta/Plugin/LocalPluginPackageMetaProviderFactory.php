<?php

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Local;

use CodeKaizen\WPPackageAutoUpdater\PackageRoot\PluginPackageRoot;
use CodeKaizen\WPPackageAutoUpdater\Parser\Slug\PluginSlugParser;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1 as LocalPluginPackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;

class LocalPluginPackageMetaProviderFactory implements PluginPackageMetaProviderFactoryContract {

	protected string $filePath;
	protected LoggerInterface $logger;
	protected ?PluginPackageMetaContract $provider;
	public function __construct( string $filePath, LoggerInterface $logger ) {
		$this->filePath = $filePath;
		$this->logger   = $logger;
	}
	public function create(): PluginPackageMetaContract {
		if ( null === $this->provider ) {
			$factory        = new LocalPluginPackageMetaProviderFactoryV1( $this->filePath, new PluginSlugParser( $this->filePath, new PluginPackageRoot() ), $this->logger );
			$this->provider = $factory->create();
		}
		return $this->provider;
	}
}
