<?php

namespace CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckInfoStrategyContract;
use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo\CheckInfoMetaFormatterPlugin;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckInfoStrategy;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaProviderFactoryContract;

class PluginCheckInfoHook implements InitializerContract, CheckInfoStrategyContract
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
        add_filter('plugins_api', array($this, 'checkInfo'), 10, 3);
    }
    public function checkInfo(bool $false, array $action, object $arg): bool|object
    {
        $formatter = new CheckInfoMetaFormatterPlugin($this->remotePackageMetaProviderFactory->create());
        $checkInfo = new CheckInfoStrategy($this->localPackageMetaProviderFactory->create(), $formatter, $this->logger);
        return $checkInfo->checkInfo($false, $action, $arg);
    }
}
