<?php
/**
 * File containing PluginORASHubAutoUpdater class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\AutoUpdater
 * @subpackage AutoUpdater
 */

namespace CodeKaizen\WPPackageAutoUpdater\AutoUpdater\ORASHub;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Plugin\Local\CachingLocalPluginPackageMetaValueServiceFactory;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Plugin\Local\StandardLocalPluginPackageMetaValueServiceFactory;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Plugin\Remote\CachingRemotePluginPackageMetaValueServiceFactory;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Plugin\Remote\StandardRemotePluginPackageMetaValueServiceFactory;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\StandardCheckUpdateHook;
use CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade\StandardDownloadUpgradeHook;
use CodeKaizen\WPPackageAutoUpdater\Accessor\Mixed\WordPressTransientProxyMixedAccessor;
use CodeKaizen\WPPackageAutoUpdater\Factory\Object\CheckInfo\PluginCheckInfoObjectFactory;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\StandardCheckInfoHook;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * PluginORASHubAutoUpdater class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\AutoUpdater
 */
class PluginORASHubAutoUpdater implements InitializerContract {

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
		$localPackageMetaProviderFactory  = new CachingLocalPluginPackageMetaValueServiceFactory(
			new StandardLocalPluginPackageMetaValueServiceFactory(
				$this->filePath,
				$this->logger
			)
		);
		$remotePackageMetaProviderFactory = new CachingRemotePluginPackageMetaValueServiceFactory(
			new StandardRemotePluginPackageMetaValueServiceFactory(
				$this->baseURL,
				$this->metaKey,
				$this->httpOptions,
				$this->logger
			)
		);
		$checkUpdateHook                  = new StandardCheckUpdateHook(
			'pre_set_site_transient_update_plugins',
			$localPackageMetaProviderFactory,
			$remotePackageMetaProviderFactory,
			$this->logger
		);
		$checkInfoHook                    = new StandardCheckInfoHook(
			'plugins_api',
			new PluginCheckInfoObjectFactory( $remotePackageMetaProviderFactory ),
			$localPackageMetaProviderFactory,
			$this->logger
		);
		$standardDownloadUpgradeHook      = new StandardDownloadUpgradeHook(
			$localPackageMetaProviderFactory,
			new WordPressTransientProxyMixedAccessor( 'update_plugins' ),
			$this->httpOptions,
			$this->logger
		);
		$checkUpdateHook->init();
		$checkInfoHook->init();
		$standardDownloadUpgradeHook->init();
	}
}
