<?php

namespace CodeKaizen\WPPackageAutoUpdater\AutoUpdater;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\ThemeCheckInfoHook;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\ThemeCheckUpdateHook;
use CodeKaizen\WPPackageAutoUpdater\PackageRoot\ThemePackageRoot;
use CodeKaizen\WPPackageAutoUpdater\Parser\Slug\ThemeSlugParser;
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1;
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1 as PackageMetaThemePackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class AutoUpdaterThemeORASHubV1 implements InitializerContract
{
    protected InitializerContract $checkUpdateHook;
    protected InitializerContract $checkInfoHook;
    public function __construct(
        string $filePath,
        string $baseURL,
        string $metaKey = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata',
        LoggerInterface $logger = new NullLogger()
    ) {
        $localPackageMetaProviderFactory = new ThemePackageMetaProviderFactoryV1($filePath, new ThemeSlugParser($filePath, new ThemePackageRoot()), $logger);
        $remotePackageMetaProviderFactory = new PackageMetaThemePackageMetaProviderFactoryV1($baseURL, $metaKey, $logger);
        $this->checkUpdateHook = new ThemeCheckUpdateHook($localPackageMetaProviderFactory, $remotePackageMetaProviderFactory, $logger);
        $this->checkInfoHook = new ThemeCheckInfoHook($localPackageMetaProviderFactory, $remotePackageMetaProviderFactory, $logger);
    }
    public function init(): void
    {
        $this->checkUpdateHook->init();
        $this->checkInfoHook->init();
    }
}
