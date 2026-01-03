<?php
/**
 * File containing StandardLocalPluginPackageMetaValueServiceFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin\Local
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Plugin\Local;

use CodeKaizen\WPPackageAutoUpdater\Value\PackageRoot\PluginPackageRootValue;
use CodeKaizen\WPPackageAutoUpdater\Value\Slug\PluginSlugValue;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Service\Value\PackageMeta\Plugin\StandardPluginPackageMetaValueServiceFactory;
use Psr\Log\LoggerInterface;

/**
 * StandardLocalPluginPackageMetaValueServiceFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin\Local
 */
class StandardLocalPluginPackageMetaValueServiceFactory implements PluginPackageMetaValueServiceFactoryContract {

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
	 * Constructor.
	 *
	 * @param string          $filePath Description for filePath.
	 * @param LoggerInterface $logger Description for logger.
	 */
	public function __construct( string $filePath, LoggerInterface $logger ) {
		$this->filePath = $filePath;
		$this->logger   = $logger;
	}
	/**
	 * Create a new instance.
	 *
	 * @return PluginPackageMetaValueServiceContract The created plugin package meta provider.
	 */
	public function create(): PluginPackageMetaValueServiceContract {
		$this->logger->debug( 'Creating new plugin package meta provider.' );
		$slugParser = new PluginSlugValue( $this->filePath, new PluginPackageRootValue(), $this->logger );
		$factory    = new StandardPluginPackageMetaValueServiceFactory(
			$this->filePath,
			$slugParser,
			$this->logger
		);
		return $factory->create();
	}
}
