<?php

namespace CodeKaizen\WPPackageAutoUpdater\AutoUpdater;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\PluginCheckInfoHook;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\PluginCheckUpdateHook;
use CodeKaizen\WPPackageAutoUpdater\PackageRoot\PluginPackageRoot;
use CodeKaizen\WPPackageAutoUpdater\Parser\Slug\PluginSlugParser;
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1;
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1 as PackageMetaPluginPackageMetaProviderFactoryV1;
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
        $localPackageMetaProviderFactory = new PluginPackageMetaProviderFactoryV1($filePath, new PluginSlugParser($filePath, new PluginPackageRoot()), $logger);
        $remotePackageMetaProviderFactory = new PackageMetaPluginPackageMetaProviderFactoryV1($baseURL, $metaKey, $logger);
        $this->checkUpdateHook = new PluginCheckUpdateHook($localPackageMetaProviderFactory, $remotePackageMetaProviderFactory, $logger);
        $this->checkInfoHook = new PluginCheckInfoHook($localPackageMetaProviderFactory, $remotePackageMetaProviderFactory, $logger);
    }
    public function init(): void
    {
        $this->checkUpdateHook->init();
        $this->checkInfoHook->init();
    }
}
