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
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PluginPackageMetaProviderContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1 as LocalPluginPackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;

/**
 * LocalPluginPackageMetaProviderFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin
 */
class LocalPluginPackageMetaProviderFactory implements PluginPackageMetaProviderFactoryContract {

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
	 * @var PluginPackageMetaProviderContract|null
	 */
	protected ?PluginPackageMetaProviderContract $provider;

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
	 * @return PluginPackageMetaProviderContract The created plugin package meta provider.
	 */
	public function create(): PluginPackageMetaProviderContract {
		if ( null === $this->provider ) {
			$slugParser     = new PluginSlugParser( $this->filePath, new PluginPackageRoot() );
			$factory        = new LocalPluginPackageMetaProviderFactoryV1(
				$this->filePath,
				$slugParser,
				$this->logger
			);
			$this->provider = $factory->create();
		}
		return $this->provider;
	}
}
