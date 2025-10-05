<?php

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Local;

use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1 as RemoteThemePackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;

class RemoteThemePackageMetaProviderFactory implements ThemePackageMetaProviderFactoryContract {

	protected string $baseURL;
	protected string $metaKey;
	protected LoggerInterface $logger;
	protected ?ThemePackageMetaContract $provider;
	public function __construct( string $baseURL, string $metaKey, LoggerInterface $logger ) {
		$this->baseURL = $baseURL;
		$this->metaKey = $metaKey;
		$this->logger  = $logger;
	}
	public function create(): ThemePackageMetaContract {
		if ( null === $this->provider ) {
			$factory        = new RemoteThemePackageMetaProviderFactoryV1( $this->baseURL, $this->metaKey, $this->logger );
			$this->provider = $factory->create();
		}
		return $this->provider;
	}
}
