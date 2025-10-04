<?php

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateMetaFormatterContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use stdClass;

class CheckUpdateMetaFormatterPlugin implements CheckUpdateMetaFormatterContract
{
    public function formatMetaForCheckUpdate(
        array $response,
        PackageMetaContract $localPackageMetaProvider,
        PackageMetaContract $remotePackageMetaProvider
    ): array {
        $metaObject = new stdClass();
        $metaObject->slug = $remotePackageMetaProvider->getShortSlug();
        $metaObject->new_version = $remotePackageMetaProvider->getVersion();
        $metaObject->package = $remotePackageMetaProvider->getDownloadURL();
        $metaObject->url = $remotePackageMetaProvider->getViewURL();
        $response[$localPackageMetaProvider->getFullSlug()] = $metaObject;
        return $response;
    }
}
