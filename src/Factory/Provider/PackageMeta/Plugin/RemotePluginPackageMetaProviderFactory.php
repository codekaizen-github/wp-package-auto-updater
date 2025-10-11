<?php
/**
 * File containing RemotePluginPackageMetaProviderFactory class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage Local
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaProviderFactoryContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1 as RemotePluginPackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;

/**
 * RemotePluginPackageMetaProviderFactory class.
 *
 * @package WPPackageAutoUpdater
 */
class RemotePluginPackageMetaProviderFactory implements PluginPackageMetaProviderFactoryContract {

	/**
	 * The base URL for the remote provider.
	 *
	 * @var string
	 */
	protected string $baseURL;

	/**
	 * The meta key used for package identification.
	 *
	 * @var string
	 */
	protected string $metaKey;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * The plugin package meta provider instance.
	 *
	 * @var PluginPackageMetaContract|null
	 */
	protected ?PluginPackageMetaContract $provider;

	/**
	 * Constructor.
	 *
	 * @param string          $baseURL Description for baseURL.
	 * @param string          $metaKey Description for metaKey.
	 * @param LoggerInterface $logger Description for logger.
	 */
	public function __construct( string $baseURL, string $metaKey, LoggerInterface $logger ) {
		$this->baseURL = $baseURL;
		$this->metaKey = $metaKey;
		$this->logger  = $logger;
	}
	/**
	 * Create a new instance.
	 *
	 * @return PluginPackageMetaContract The created plugin package meta provider.
	 */
	public function create(): PluginPackageMetaContract {
		if ( null === $this->provider ) {
			$factory        = new RemotePluginPackageMetaProviderFactoryV1(
				$this->baseURL,
				$this->metaKey,
				$this->logger
			);
			$this->provider = $factory->create();
		}
		return $this->provider;
	}
}
