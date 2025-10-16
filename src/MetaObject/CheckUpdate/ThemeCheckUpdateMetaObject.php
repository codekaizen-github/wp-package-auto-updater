<?php
/**
 * PluginCheckUpdateMetaObject
 *
 * @package CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckUpdate;

use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use stdClass;

/**
 * Undocumented class
 */
class ThemeCheckUpdateMetaObject extends stdClass {
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
	public ?string $package;
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
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
	public ?string $id;
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
	public ?string $requires;
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
	public ?string $requires_php; // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
	/**
	 * Undocumented variable
	 *
	 * @var array<string>
	 */
	public array $requires_plugins; // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
	/**
	 * Undocumented function
	 *
	 * @param ThemePackageMetaContract $provider Provider.
	 */
	public function __construct( ThemePackageMetaContract $provider ) {
		$this->slug = $provider->getShortSlug();
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->new_version = $provider->getVersion();
		$this->package     = $provider->getDownloadURL();
		$this->url         = $provider->getViewURL();
		$this->id          = $provider->getFullSlug();
		$this->icons       = [];
		$this->banners     = [];
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->banners_rtl = [];
		$this->requires    = $provider->getRequiresWordPressVersion();
		$this->tested      = $provider->getTested();
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->requires_php = $provider->getRequiresPHPVersion();
		// phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
		$this->requires_plugins = []; // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
	}
}
