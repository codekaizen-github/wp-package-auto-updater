<?php

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateFormatterContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use stdClass;

class CheckUpdateFormatterTheme implements CheckUpdateFormatterContract {

	/**
	 * Format the update check response for themes.
	 *
	 * @param array<string, mixed> $response               The original response array.
	 * @param PackageMetaContract  $localPackageMetaProvider  Local package metadata.
	 * @param PackageMetaContract  $remotePackageMetaProvider Remote package metadata.
	 * @return array<string, mixed>                        The formatted response.
	 */
	public function formatForCheckUpdate(
		array $response,
		PackageMetaContract $localPackageMetaProvider,
		PackageMetaContract $remotePackageMetaProvider
	): array {
		$metaObject              = new stdClass();
		$metaObject->slug        = $remotePackageMetaProvider->getShortSlug();
		$metaObject->new_version = $remotePackageMetaProvider->getVersion();
		$metaObject->package     = $remotePackageMetaProvider->getDownloadURL();
		$metaObject->url         = $remotePackageMetaProvider->getViewURL();
		$response[ $localPackageMetaProvider->getFullSlug() ] = (array) $metaObject;
		return $response;
	}
}
