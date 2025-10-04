<?php

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;

interface CheckUpdateFormatterContract
{
    public function formatMetaForCheckUpdate(
        array $response,
        PackageMetaContract $localPackageMetaProvider,
        PackageMetaContract $remotePackageMetaProvider
    ): array;
}
