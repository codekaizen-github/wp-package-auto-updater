<?php
/**
 * CheckUpdateMetaObject
 *
 * @package CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate
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
	 * @param PackageMetaContract $provider Provider.
	 */
	public function __construct( PackageMetaContract $provider ) {
		$this->id   = $provider->getFullSlug();
		$this->slug = $provider->getShortSlug();
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->new_version = $provider->getVersion();
		$this->url         = $provider->getViewURL();
		$this->package     = $provider->getDownloadURL();
		$this->icons       = [];
		$this->banners     = [];
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->banners_rtl = [];
		$this->tested      = $provider->getTested();
		$this->requires    = $provider->getRequiresWordPressVersion();
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->requires_php = $provider->getRequiresPHPVersion();
	}
}
