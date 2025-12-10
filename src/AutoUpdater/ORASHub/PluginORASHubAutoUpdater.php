<?php
/**
 * File containing PluginORASHubAutoUpdater class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\AutoUpdater
 * @subpackage AutoUpdater
 */

namespace CodeKaizen\WPPackageAutoUpdater\AutoUpdater\ORASHub;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin\LocalPluginPackageMetaProviderFactory;
use CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin\RemotePluginPackageMetaProviderFactory;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\PluginCheckInfoHook;
use CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\PluginCheckUpdateHook;
use CodeKaizen\WPPackageAutoUpdater\Hook\DownloadUpgrade\DownloadUpgradeHook;
use CodeKaizen\WPPackageAutoUpdater\Provider\WordPress\Transient\TransientWordPressProvider;
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
		$localPackageMetaProviderFactory  = new LocalPluginPackageMetaProviderFactory( $this->filePath, $this->logger );
		$remotePackageMetaProviderFactory = new RemotePluginPackageMetaProviderFactory(
			$this->baseURL,
			$this->metaKey,
			$this->httpOptions,
			$this->logger
		);
		$checkUpdateHook                  = new PluginCheckUpdateHook(
			$localPackageMetaProviderFactory,
			$remotePackageMetaProviderFactory,
			$this->logger
		);
		$checkInfoHook                    = new PluginCheckInfoHook(
			$localPackageMetaProviderFactory,
			$remotePackageMetaProviderFactory,
			$this->logger
		);
		$downloadUpgradeHook              = new DownloadUpgradeHook(
			$localPackageMetaProviderFactory,
			new TransientWordPressProvider( 'update_plugins' ),
			$this->httpOptions,
			$this->logger
		);
		$checkUpdateHook->init();
		$checkInfoHook->init();
		$downloadUpgradeHook->init();
	}
}
