<?php

namespace CodeKaizen\WPPackageAutoUpdater\AutoUpdater;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Local\LocalPluginPackageMetaProviderFactory;
use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Local\RemotePluginPackageMetaProviderFactory;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\PluginCheckInfoHook;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\PluginCheckUpdateHook;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class AutoUpdaterPluginORASHubV1 implements InitializerContract
{
    protected InitializerContract $checkUpdateHook;
    protected InitializerContract $checkInfoHook;
    public function __construct(
        string $filePath,
        string $baseURL,
        string $metaKey = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata',
        LoggerInterface $logger = new NullLogger()
    ) {
        $localPackageMetaProviderFactory = new LocalPluginPackageMetaProviderFactory($filePath, $logger);
        $remotePackageMetaProviderFactory = new RemotePluginPackageMetaProviderFactory($baseURL, $metaKey, $logger);
        $this->checkUpdateHook = new PluginCheckUpdateHook($localPackageMetaProviderFactory, $remotePackageMetaProviderFactory, $logger);
        $this->checkInfoHook = new PluginCheckInfoHook($localPackageMetaProviderFactory, $remotePackageMetaProviderFactory, $logger);
    }
    public function init(): void
    {
        $this->checkUpdateHook->init();
        $this->checkInfoHook->init();
    }
}
