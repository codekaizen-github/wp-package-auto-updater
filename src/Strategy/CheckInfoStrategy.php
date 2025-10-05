<?php

namespace CodeKaizen\WPPackageAutoUpdater\Strategy;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckInfoStrategyContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use Psr\Log\LoggerInterface;

class CheckInfoStrategy implements CheckInfoStrategyContract
{
    protected PackageMetaContract $localPackageMetaProvider;
    protected CheckInfoFormatterContract $formatter;
    private LoggerInterface $logger;
    public function __construct(PackageMetaContract $localPackageMetaProvider, CheckInfoFormatterContract $formatter, LoggerInterface $logger)
    {
        $this->localPackageMetaProvider = $localPackageMetaProvider;
        $this->formatter = $formatter;
        $this->logger = $logger;
    }
    public function checkInfo(bool $false, array $action, object $arg): bool|object
    {
        // Check if this is for our package
        if (!$arg->slug || $arg->slug !== $this->localPackageMetaProvider->getFullSlug()) {
            return $false;
        }
        $this->logger->debug("Providing package info for: " . $arg->slug);

        // Get metadata from remote source
        $meta = $this->formatter->formatForCheckInfo();
        if (!$meta) {
            $this->logger->debug("Failed to get metadata for package info");
            return $false;
        }

        $this->logger->debug("Returning package info with properties: " . implode(', ', array_keys((array)$meta)));

        return $meta;
    }
}
