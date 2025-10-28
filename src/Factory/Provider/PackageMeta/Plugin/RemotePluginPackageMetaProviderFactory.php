<?php
/**
 * File containing RemotePluginPackageMetaProviderFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin
 * @subpackage Local
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin;

// phpcs:disable Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Argument\Filter\Factory\Provider\PackageMeta\Remote\CreateRemotePackageMetaProviderFactoryFilterArgument;
use CodeKaizen\WPPackageAutoUpdater\Contract\Argument\Filter\Factory\Provider\PackageMeta\Remote\CreateRemotePackageMetaProviderFactoryFilterArgumentContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PluginPackageMetaProviderContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1 as RemotePluginPackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

// phpcs:enable Generic.Files.LineLength.TooLong

/**
 * RemotePluginPackageMetaProviderFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin
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
	 * The plugin package meta provider instance.
	 *
	 * @var PluginPackageMetaProviderContract|null
	 */
	protected ?PluginPackageMetaProviderContract $provider;

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
	 * @return PluginPackageMetaProviderContract The created plugin package meta provider.
	 * @throws UnexpectedValueException If the provided options are invalid.
	 */
	public function create(): PluginPackageMetaProviderContract {
		if ( null === $this->provider ) {
			// phpcs:disable Generic.Files.LineLength.TooLong
			/**
			 * Filter
			 *
			 * @param CreateRemotePackageMetaProviderFactoryFilterArgumentContract $options The options for creating the factory.
			 */
			$options = apply_filters(
				'wp_package_auto_updater_remote_plugin_package_meta_provider_factory_v1_instance_options',
				new CreateRemotePackageMetaProviderFactoryFilterArgument(
					$this->baseURL,
					$this->metaKey,
					$this->httpOptions,
					$this->logger
				)
			);
			// phpcs:enable Generic.Files.LineLength.TooLong
			/**
			 * Cannot trust the type coming from the filter.
			 *
			 * @var mixed $options
			 */
			if ( ! $options instanceof CreateRemotePackageMetaProviderFactoryFilterArgumentContract ) {
				throw new UnexpectedValueException( 'Invalid options provided' );
			}
			$factory        = new RemotePluginPackageMetaProviderFactoryV1(
				$options->getBaseURL(),
				$options->getMetaKey(),
				$options->getHttpOptions(),
				$options->getLogger()
			);
			$this->provider = $factory->create();
		}
		return $this->provider;
	}
}
