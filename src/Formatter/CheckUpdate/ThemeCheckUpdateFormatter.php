<?php

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateFormatterContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use stdClass;

class CheckUpdateFormatterTheme implements CheckUpdateFormatterContract
{
    public function formatForCheckUpdate(
        array $response,
        PackageMetaContract $localPackageMetaProvider,
        PackageMetaContract $remotePackageMetaProvider
    ): array {
        $metaObject = new stdClass();
        $metaObject->slug = $remotePackageMetaProvider->getShortSlug();
        $metaObject->new_version = $remotePackageMetaProvider->getVersion();
        $metaObject->package = $remotePackageMetaProvider->getDownloadURL();
        $metaObject->url = $remotePackageMetaProvider->getViewURL();
        $response[$localPackageMetaProvider->getFullSlug()] = (array) $metaObject;
        return $response;
    }
}
