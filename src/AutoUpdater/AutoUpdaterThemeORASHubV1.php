<?php
/**
 * File containing AutoUpdaterThemeORASHubV1 class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage AutoUpdater
 */

namespace CodeKaizen\WPPackageAutoUpdater\AutoUpdater;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Local\LocalThemePackageMetaProviderFactory;
use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Local\RemoteThemePackageMetaProviderFactory;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\ThemeCheckInfoHook;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\ThemeCheckUpdateHook;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * AutoUpdaterThemeORASHubV1 class.
 *
 * @package WPPackageAutoUpdater
 */
class AutoUpdaterThemeORASHubV1 implements InitializerContract {

	/**
	 * The check update hook.
	 *
	 * @var InitializerContract
	 */
	protected InitializerContract $checkUpdateHook;

	/**
	 * The check info hook.
	 *
	 * @var InitializerContract
	 */
	protected InitializerContract $checkInfoHook;
	/**
	 * Constructor.
	 *
	 * @param string          $filePath Description for filePath.
	 * @param string          $baseURL Description for baseURL.
	 * @param string          $metaKey Description for metaKey.
	 * @param LoggerInterface $logger Description for logger.
	 *
	 * @return mixed
	 */
	public function __construct(
		string $filePath,
		string $baseURL,
		string $metaKey = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata',
		LoggerInterface $logger = new NullLogger()
	) {
		$localPackageMetaProviderFactory  = new LocalThemePackageMetaProviderFactory( $filePath, $logger );
		$remotePackageMetaProviderFactory = new RemoteThemePackageMetaProviderFactory( $baseURL, $metaKey, $logger );
		$this->checkUpdateHook            = new ThemeCheckUpdateHook(
			$localPackageMetaProviderFactory,
			$remotePackageMetaProviderFactory,
			$logger
		);
		$this->checkInfoHook              = new ThemeCheckInfoHook(
			$localPackageMetaProviderFactory,
			$remotePackageMetaProviderFactory,
			$logger
		);
	}
	/**
	 * Initialize the component.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->checkUpdateHook->init();
		$this->checkInfoHook->init();
	}
}
