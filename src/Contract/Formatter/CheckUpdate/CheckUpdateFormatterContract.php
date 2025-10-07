<?php

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;

interface CheckUpdateFormatterContract {

	/**
	 * Format the update check response.
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
	): array;
}
