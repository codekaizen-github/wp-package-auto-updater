<?php
/**
 * File containing LocalPluginPackageMetaProviderFactory class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage Local
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Local;

use CodeKaizen\WPPackageAutoUpdater\PackageRoot\PluginPackageRoot;
use CodeKaizen\WPPackageAutoUpdater\Parser\Slug\PluginSlugParser;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderLocal\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryV1 as LocalPluginPackageMetaProviderFactoryV1;
use Psr\Log\LoggerInterface;

/**
 * LocalPluginPackageMetaProviderFactory class.
 *
 * @package WPPackageAutoUpdater
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
	 * @var PluginPackageMetaContract|null
	 */
	protected ?PluginPackageMetaContract $provider;

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
	 * @return PluginPackageMetaContract The created plugin package meta provider.
	 */
	public function create(): PluginPackageMetaContract {
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
