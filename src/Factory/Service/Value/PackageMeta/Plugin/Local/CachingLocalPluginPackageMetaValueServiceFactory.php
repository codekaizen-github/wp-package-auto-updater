<?php
/**
 * File containing CachingLocalPluginPackageMetaValueServiceFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin\Local
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Plugin\Local;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;

/**
 * CachingLocalPluginPackageMetaValueServiceFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Plugin\Local
 */
class CachingLocalPluginPackageMetaValueServiceFactory implements PluginPackageMetaValueServiceFactoryContract {

	/**
	 * The decorated factory instance.
	 *
	 * @var PluginPackageMetaValueServiceFactoryContract
	 */
	protected PluginPackageMetaValueServiceFactoryContract $factory;

	/**
	 * The plugin package meta provider instance.
	 *
	 * @var PluginPackageMetaValueServiceContract|null
	 */
	protected ?PluginPackageMetaValueServiceContract $provider;

	/**
	 * Constructor.
	 *
	 * @param PluginPackageMetaValueServiceFactoryContract $factory The factory to decorate.
	 */
	public function __construct( PluginPackageMetaValueServiceFactoryContract $factory ) {
		$this->factory  = $factory;
		$this->provider = null;
	}
	/**
	 * Create a new instance.
	 *
	 * @return PluginPackageMetaValueServiceContract The created plugin package meta provider.
	 */
	public function create(): PluginPackageMetaValueServiceContract {
		if ( null === $this->provider ) {
			$this->provider = $this->factory->create();
		}
		return $this->provider;
	}
}
