<?php
/**
 * PluginCheckUpdateMetaObject
 *
 *   @package CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate;

use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use stdClass;

/**
 * Undocumented class
 */
class CheckUpdateMetaObject extends stdClass {
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	public string $slug;
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $newVersion;
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $package;
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $url;
	/**
	 * Undocumented function
	 *
	 * @param PackageMetaContract $provider Provider.
	 */
	public function __construct( PackageMetaContract $provider ) {
		$this->slug       = $provider->getShortSlug();
		$this->newVersion = $provider->getVersion();
		$this->package    = $provider->getDownloadURL();
		$this->url        = $provider->getViewURL();
	}
}
