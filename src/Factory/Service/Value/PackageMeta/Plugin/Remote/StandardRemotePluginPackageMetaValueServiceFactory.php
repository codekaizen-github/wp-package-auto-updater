<?php
/**
 * File containing StandardRemotePluginPackageMetaValueServiceFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin\Remote
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Plugin\Remote;

// phpcs:disable Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Argument\Filter\Factory\Value\PackageMeta\Remote\CreateRemotePackageMetaValueFactoryFilterArgument;
use CodeKaizen\WPPackageAutoUpdater\Contract\Argument\Filter\Factory\Value\PackageMeta\Remote\CreateRemotePackageMetaValueFactoryFilterArgumentContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
use CodeKaizen\WPPackageMetaProviderORASHub\Factory\Service\Value\PackageMeta\Plugin\StandardPluginPackageMetaValueServiceFactory;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

// phpcs:enable Generic.Files.LineLength.TooLong

/**
 * StandardRemotePluginPackageMetaValueServiceFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin\Remote
 */
class StandardRemotePluginPackageMetaValueServiceFactory implements PluginPackageMetaValueServiceFactoryContract {

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
	}
	/**
	 * Create a new instance.
	 *
	 * @return PluginPackageMetaValueServiceContract The created plugin package meta provider.
	 * @throws UnexpectedValueException If the provided options are invalid.
	 */
	public function create(): PluginPackageMetaValueServiceContract {
		$this->logger->debug( 'Creating new StandardRemotePluginPackageMetaValueServiceFactory instance.' );
		// phpcs:disable Generic.Files.LineLength.TooLong
		$argument = new CreateRemotePackageMetaValueFactoryFilterArgument(
			$this->baseURL,
			$this->metaKey,
			$this->httpOptions,
			$this->logger
		);
		$this->logger->debug(
			'Before applying filter in StandardRemotePluginPackageMetaValueServiceFactory.',
			[
				'argument' => $argument,
			]
		);
		/**
		 * Filter
		 *
		 * @param CreateRemotePackageMetaValueFactoryFilterArgumentContract $options The options for creating the factory.
		 */
		$options = apply_filters(
			'wp_package_auto_updater_remote_plugin_package_meta_provider_factory_v1_instance_options',
			$argument
		);
		$this->logger->debug(
			'After applying filter in StandardRemotePluginPackageMetaValueServiceFactory.',
			[
				'options' => $options,
			]
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		/**
		 * Cannot trust the type coming from the filter.
		 *
		 * @var mixed $options
		 */
		if ( ! $options instanceof CreateRemotePackageMetaValueFactoryFilterArgumentContract ) {
			$this->logger->error( 'Invalid options provided to StandardRemotePluginPackageMetaValueServiceFactory.' );
			throw new UnexpectedValueException( 'Invalid options provided' );
		}
		$factory = new StandardPluginPackageMetaValueServiceFactory(
			$options->getBaseURL(),
			$options->getMetaKey(),
			$options->getHttpOptions(),
			$options->getLogger()
		);
		return $factory->create();
	}
}
