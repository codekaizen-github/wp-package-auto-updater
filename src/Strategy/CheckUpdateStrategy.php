<?php

namespace CodeKaizen\WPPackageAutoUpdater\Strategy;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateMetaFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckUpdateStrategyContract as StrategyCheckUpdateStrategyContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use Psr\Log\LoggerInterface;
use Exception;

class CheckUpdateStrategy implements StrategyCheckUpdateStrategyContract
{
    protected PackageMetaContract $localPackageMetaProvider;
    protected PackageMetaContract $remotePackageMetaProvider;
    protected CheckUpdateMetaFormatterContract $formatter;
    protected LoggerInterface $logger;
    function __construct(PackageMetaContract $localPackageMetaProvider, PackageMetaContract $remotePackageMetaProvider, CheckUpdateMetaFormatterContract $formatter, LoggerInterface $logger)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->remotePackageMetaProvider = $remotePackageMetaProvider;
        $this->formatter = $formatter;
        $this->logger = $logger;
    }
    public function checkUpdate(object $transient): object
    {
        $this->logger->debug("Checking for updates " . $this->localPackageMetaProvider->getFullSlug());
        if (empty($transient->checked)) {
            $this->logger->debug("No checked packages in transient, skipping");
            return $transient;
        }
        try {
            if (version_compare($this->localPackageMetaProvider->getVersion(), $this->remotePackageMetaProvider->getVersion(), '<')) {
                $transient->response = $this->formatter->formatMetaForCheckUpdate($transient->response, $this->localPackageMetaProvider, $this->remotePackageMetaProvider);
            } else {
                $transient->no_update = $this->formatter->formatMetaForCheckUpdate($transient->no_update, $this->localPackageMetaProvider, $this->remotePackageMetaProvider);
            }
        } catch (Exception $e) {
            $this->logger->error('Unable to get remote package version: ' . $e);
            return $transient;
        }
        return $transient;
    }
}
