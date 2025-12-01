<?php
/**
 * File containing LocalPluginPackageMetaProviderFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin
 * @subpackage Local
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin;

use CodeKaizen\WPPackageAutoUpdater\PackageRoot\PluginPackageRoot;
use CodeKaizen\WPPackageAutoUpdater\Parser\Slug\PluginSlugParser;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Service\Value\PackageMeta\Plugin\StandardPluginPackageMetaValueServiceFactory;
use Psr\Log\LoggerInterface;

/**
 * LocalPluginPackageMetaProviderFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin
 */
class LocalPluginPackageMetaProviderFactory implements PluginPackageMetaValueServiceFactoryContract {

	/**
	 * The plugin file path.
	 *
	 * @var string
	 */
	protected string $filePath;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * The plugin package meta provider instance.
	 *
	 * @var PluginPackageMetaValueServiceContract|null
	 */
	protected ?PluginPackageMetaValueServiceContract $provider;

	/**
	 * Constructor.
	 *
	 * @param string          $filePath Description for filePath.
	 * @param LoggerInterface $logger Description for logger.
	 */
	public function __construct( string $filePath, LoggerInterface $logger ) {
		$this->filePath = $filePath;
		$this->logger   = $logger;
		$this->provider = null;
	}
	/**
	 * Create a new instance.
	 *
	 * @return PluginPackageMetaValueServiceContract The created plugin package meta provider.
	 */
	public function create(): PluginPackageMetaValueServiceContract {
		if ( null === $this->provider ) {
			$this->logger->debug( 'Creating new plugin package meta provider.' );
			$slugParser     = new PluginSlugParser( $this->filePath, new PluginPackageRoot(), $this->logger );
			$factory        = new StandardPluginPackageMetaValueServiceFactory(
				$this->filePath,
				$slugParser,
				$this->logger
			);
			$this->provider = $factory->create();
		}
		return $this->provider;
	}
}
