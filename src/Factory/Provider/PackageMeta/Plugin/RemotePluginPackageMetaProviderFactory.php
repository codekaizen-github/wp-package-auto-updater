<?php

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Local;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1 as RemotePluginPackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;

class RemotePluginPackageMetaProviderFactory implements PluginPackageMetaProviderFactoryContract
{
    protected string $baseURL;
    protected string $metaKey;
    protected LoggerInterface $logger;
    protected ?PluginPackageMetaContract $provider;
    public function __construct(string $baseURL, string $metaKey, LoggerInterface $logger)
    {
        $this->baseURL = $baseURL;
        $this->metaKey = $metaKey;
        $this->logger = $logger;
    }
    public function create(): PluginPackageMetaContract
    {
        if (null === $this->provider) {
            $factory =  new RemotePluginPackageMetaProviderFactoryV1($this->baseURL, $this->metaKey, $this->logger);
            $this->provider = $factory->create();
        }
        return $this->provider;
    }
}
