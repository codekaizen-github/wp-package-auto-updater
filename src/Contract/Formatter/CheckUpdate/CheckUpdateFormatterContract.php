<?php
/**
 * File containing CheckUpdateFormatterContract Interface class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate
 * @subpackage CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;

interface CheckUpdateFormatterContract {

	/**
	 * Format For Check Update.
	 *
	 * @param array<string, mixed> $response The original response array.
	 *
	 * @return array<string, mixed> The formatted response with update information.
	 */
	public function formatForCheckUpdate(
		array $response,
	): array;
}
