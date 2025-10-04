<?php

namespace CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate;

use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckUpdateStrategyContract;
use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate\CheckUpdateMetaFormatterPlugin;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckUpdateStrategy;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaProviderFactoryContract;

class PluginCheckUpdateHook implements InitializerContract, CheckUpdateStrategyContract
{
    protected PluginPackageMetaProviderFactoryContract $localPackageMetaProviderFactory;
    protected PluginPackageMetaProviderFactoryContract $remotePackageMetaProviderFactory;
    protected LoggerInterface $logger;
    public function __construct(PluginPackageMetaProviderFactoryContract $localPackageMetaProviderFactory, PluginPackageMetaProviderFactoryContract $remotePackageMetaProviderFactory, LoggerInterface $logger)
    {
        $this->localPackageMetaProviderFactory = $localPackageMetaProviderFactory;
        $this->remotePackageMetaProviderFactory = $remotePackageMetaProviderFactory;
        $this->logger = $logger;
    }
    public function init(): void
    {
        add_filter('pre_set_site_transient_update_plugins', array($this, 'checkUpdate'));
    }
    public function checkUpdate(object $transient): object
    {
        $formatter = new CheckUpdateMetaFormatterPlugin();
        $checkUpdate = new CheckUpdateStrategy($this->localPackageMetaProviderFactory->create(), $this->remotePackageMetaProviderFactory->create(), $formatter, $this->logger);
        return $checkUpdate->checkUpdate($transient);
    }
}
