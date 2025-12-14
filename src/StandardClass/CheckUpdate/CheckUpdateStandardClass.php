<?php
/**
 * CheckUpdateStandardClass
 *
 * @package CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\StandardClass\CheckUpdate;

use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use stdClass;

/**
 * Undocumented class
 */
class CheckUpdateStandardClass extends stdClass {
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	public string $id;
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
	public ?string $new_version; // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $url;
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $package;
	/**
	 * Undocumented variable
	 *
	 * @var array<string,string>
	 */
	public array $icons;
	/**
	 * Undocumented variable
	 *
	 * @var array<string,string>
	 */
	public array $banners;
	/**
	 * Undocumented variable
	 *
	 * @var array<string,string>
	 */
	public array $banners_rtl; // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $tested;
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $requires;
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $requires_php; // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
	/**
	 * Undocumented function
	 *
	 * @param PackageMetaValueContract $provider Provider.
	 */
	public function __construct( PackageMetaValueContract $provider ) {
		$this->id   = $provider->getFullSlug();
		$this->slug = $provider->getShortSlug();
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->new_version = $provider->getVersion();
		$this->url         = $provider->getViewURL();
		$this->package     = $provider->getDownloadURL();
		$this->icons       = $provider->getIcons();
		$this->banners     = $provider->getBanners();
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->banners_rtl = $provider->getBannersRTL();
		$this->tested      = $provider->getTested();
		$this->requires    = $provider->getRequiresWordPressVersion();
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->requires_php = $provider->getRequiresPHPVersion();
	}
}
