<?php
/**
 * File containing RemoteThemePackageMetaProviderFactory class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage Local
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Local;

use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1 as RemoteThemePackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;

/**
 * RemoteThemePackageMetaProviderFactory class.
 *
 * @package WPPackageAutoUpdater
 */
class RemoteThemePackageMetaProviderFactory implements ThemePackageMetaProviderFactoryContract {

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
	 * The theme package meta provider instance.
	 *
	 * @var ThemePackageMetaContract|null
	 */
	protected ?ThemePackageMetaContract $provider;
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
	 * @return ThemePackageMetaContract The created theme package meta provider.
	 */
	public function create(): ThemePackageMetaContract {
		if ( null === $this->provider ) {
			$factory        = new RemoteThemePackageMetaProviderFactoryV1(
				$this->baseURL,
				$this->metaKey,
				$this->logger
			);
			$this->provider = $factory->create();
		}
		return $this->provider;
	}
}
