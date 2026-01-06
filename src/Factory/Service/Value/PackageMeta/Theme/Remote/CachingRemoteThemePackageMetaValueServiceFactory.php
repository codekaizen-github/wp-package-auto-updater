<?php
/**
 * File containing CachingRemoteThemePackageMetaValueServiceFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Theme\Remote
 */

namespace CodeKaizen\WPPackageAutoUpdater\Factory\Service\Value\PackageMeta\Theme\Remote;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\ThemePackageMetaValueServiceFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\ThemePackageMetaValueServiceContract;

/**
 * CachingRemoteThemePackageMetaValueServiceFactory class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Factory\Provider\PackageMeta\Theme\Remote
 */
class CachingRemoteThemePackageMetaValueServiceFactory implements ThemePackageMetaValueServiceFactoryContract {

	/**
	 * The decorated factory instance.
	 *
	 * @var ThemePackageMetaValueServiceFactoryContract
	 */
	protected ThemePackageMetaValueServiceFactoryContract $factory;

	/**
	 * The theme package meta provider instance.
	 *
	 * @var ThemePackageMetaValueServiceContract|null
	 */
	protected ?ThemePackageMetaValueServiceContract $provider;

	/**
	 * Constructor.
	 *
	 * @param ThemePackageMetaValueServiceFactoryContract $factory The factory to decorate.
	 */
	public function __construct( ThemePackageMetaValueServiceFactoryContract $factory ) {
		$this->factory  = $factory;
		$this->provider = null;
	}
	/**
	 * Create a new instance.
	 *
	 * @return ThemePackageMetaValueServiceContract The created theme package meta provider.
	 */
	public function create(): ThemePackageMetaValueServiceContract {
		if ( null === $this->provider ) {
			$this->provider = $this->factory->create();
		}
		return $this->provider;
	}
}
