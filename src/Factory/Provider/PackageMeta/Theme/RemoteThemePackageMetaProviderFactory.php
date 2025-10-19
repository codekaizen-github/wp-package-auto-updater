<?php
/**
 * File containing RemoteThemePackageMetaProviderFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Theme
 * @subpackage Local
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Theme;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\ThemePackageMetaProviderContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\ThemePackageMetaProviderFactoryV1 as RemoteThemePackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;

/**
 * RemoteThemePackageMetaProviderFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Theme
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
	 * The HTTP options for the remote requests.
	 *
	 * @var array<string,mixed>
	 */
	protected array $httpOptions;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * The theme package meta provider instance.
	 *
	 * @var ThemePackageMetaProviderContract|null
	 */
	protected ?ThemePackageMetaProviderContract $provider;
	/**
	 * Constructor.
	 *
	 * @param string              $baseURL Description for baseURL.
	 * @param string              $metaKey Description for metaKey.
	 * @param array<string,mixed> $httpOptions Description for httpOptions.
	 * @param LoggerInterface     $logger Description for logger.
	 */
	public function __construct( string $baseURL, string $metaKey, array $httpOptions, LoggerInterface $logger ) {
		$this->baseURL     = $baseURL;
		$this->metaKey     = $metaKey;
		$this->httpOptions = $httpOptions;
		$this->logger      = $logger;
		$this->provider    = null;
	}
	/**
	 * Create a new instance.
	 *
	 * @return ThemePackageMetaProviderContract The created theme package meta provider.
	 */
	public function create(): ThemePackageMetaProviderContract {
		if ( null === $this->provider ) {
			$factory        = new RemoteThemePackageMetaProviderFactoryV1(
				$this->baseURL,
				$this->metaKey,
				$this->httpOptions,
				$this->logger
			);
			$this->provider = $factory->create();
		}
		return $this->provider;
	}
}
