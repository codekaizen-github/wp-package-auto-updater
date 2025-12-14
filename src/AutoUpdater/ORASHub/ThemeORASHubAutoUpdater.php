<?php
/**
 * File containing ThemeORASHubAutoUpdater class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\AutoUpdater
 * @subpackage AutoUpdater
 */

namespace CodeKaizen\WPPackageAutoUpdater\AutoUpdater\ORASHub;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Theme\LocalThemePackageMetaValueServiceFactory;
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Theme\RemoteThemePackageMetaValueServiceFactory;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\ThemeCheckInfoHook;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\ThemeCheckUpdateHook;
use CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade\StandardDownloadUpgradeHook;
use CodeKaizen\WPPackageAutoUpdater\Provider\WordPress\Transient\TransientWordPressProvider;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * ThemeORASHubAutoUpdater class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\AutoUpdater
 */
class ThemeORASHubAutoUpdater implements InitializerContract {

	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected string $filePath;
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected string $baseURL;
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	protected string $metaKey;
	/**
	 * Undocumented variable
	 *
	 * @var array<string,mixed>
	 */
	protected array $httpOptions;
	/**
	 * Undocumented variable
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;
	/**
	 * Constructor.
	 *
	 * @param string              $filePath Description for filePath.
	 * @param string              $baseURL Description for baseURL.
	 * @param string              $metaKey Description for metaKey.
	 * @param array<string,mixed> $httpOptions Description for httpOptions.
	 * @param LoggerInterface     $logger Description for logger.
	 *
	 * @return mixed
	 */
	public function __construct(
		string $filePath,
		string $baseURL,
		string $metaKey = 'org.codekaizen-github.wp-package-deploy.wp-package-metadata',
		array $httpOptions = [],
		LoggerInterface $logger = new NullLogger()
	) {
		$this->filePath    = $filePath;
		$this->baseURL     = $baseURL;
		$this->metaKey     = $metaKey;
		$this->httpOptions = $httpOptions;
		$this->logger      = $logger;
	}
	/**
	 * Initialize the component.
	 *
	 * @return void
	 */
	public function init(): void {
		$localPackageMetaProviderFactory  = new LocalThemePackageMetaValueServiceFactory(
			$this->filePath,
			$this->logger
		);
		$remotePackageMetaProviderFactory = new RemoteThemePackageMetaValueServiceFactory(
			$this->baseURL,
			$this->metaKey,
			$this->httpOptions,
			$this->logger
		);
		$checkUpdateHook                  = new ThemeCheckUpdateHook(
			$localPackageMetaProviderFactory,
			$remotePackageMetaProviderFactory,
			$this->logger
		);
		$checkInfoHook                    = new ThemeCheckInfoHook(
			$localPackageMetaProviderFactory,
			$remotePackageMetaProviderFactory,
			$this->logger
		);
		$standardDownloadUpgradeHook      = new StandardDownloadUpgradeHook(
			$localPackageMetaProviderFactory,
			new TransientWordPressProvider( 'update_themes' ),
			$this->httpOptions,
			$this->logger
		);
		$checkUpdateHook->init();
		$checkInfoHook->init();
		$standardDownloadUpgradeHook->init();
	}
}
