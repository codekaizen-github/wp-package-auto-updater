<?php
/**
 * File containing ThemeCheckUpdateFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate
 * @subpackage CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckUpdate\CheckUpdateFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate\ThemeCheckUpdateMetaObject;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;

/**
 * ThemeCheckUpdateFormatter class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate
 */
class ThemeCheckUpdateFormatter implements CheckUpdateFormatterContract {
	/**
	 * The local plugin package meta provider.
	 *
	 * @var ThemePackageMetaContract
	 */
	protected ThemePackageMetaContract $localPackageMetaProvider;
	/**
	 * The remote plugin package meta provider.
	 *
	 * @var ThemePackageMetaContract
	 */
	protected ThemePackageMetaContract $remotePackageMetaProvider;
	/**
	 * Constructor.
	 *
	 * @param ThemePackageMetaContract $localPackageMetaProvider  The local package meta provider.
	 * @param ThemePackageMetaContract $remotePackageMetaProvider The remote package meta provider.
	 */
	public function __construct(
		ThemePackageMetaContract $localPackageMetaProvider,
		ThemePackageMetaContract $remotePackageMetaProvider
	) {
		$this->localPackageMetaProvider  = $localPackageMetaProvider;
		$this->remotePackageMetaProvider = $remotePackageMetaProvider;
		// Constructor can be empty or used for dependency injection if needed in the future.
	}

	/**
	 * Format For Check Update.
	 *
	 * @param array<string, mixed> $response The original response array.
	 *
	 * @return array<string, mixed> The formatted response with update information.
	 */
	public function formatForCheckUpdate(
		array $response,
	): array {
		$metaObject = new ThemeCheckUpdateMetaObject( $this->remotePackageMetaProvider );
		$response[ $this->localPackageMetaProvider->getFullSlug() ] = (array) $metaObject;
		return $response;
	}
}
