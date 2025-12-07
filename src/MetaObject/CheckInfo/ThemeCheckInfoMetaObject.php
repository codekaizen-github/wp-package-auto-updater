<?php
/**
 * PluginCheckInfoMetaObject
 *
 *   @package CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\MetaObject\CheckInfo;

use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use stdClass;

/**
 * Undocumented class
 */
class ThemeCheckInfoMetaObject extends stdClass {
	/**
	 * Undocumented variable
	 *
	 * @var string
	 */
	public string $name;

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
	public ?string $version;
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $author;
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
	public ?string $requiresPhp;
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $homepage;
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $downloadLink;
	/**
	 * Undocumented variable
	 *
	 * @var string|null
	 */
	public ?string $updateUri;
	/**
	 * Undocumented variable
	 *
	 * @var array<string, string>
	 */
	public array $sections;
	/**
	 * Undocumented variable
	 *
	 * @var string[]
	 */
	public array $tags;
	/**
	 * Undocumented variable
	 *
	 * @var boolean
	 */
	public bool $external;
	/**
	 * Undocumented function
	 *
	 * @param PackageMetaValueContract $provider Provider.
	 */
	public function __construct( PackageMetaValueContract $provider ) {
		$this->name    = $provider->getName();
		$this->slug    = $provider->getShortSlug();
		$this->version = $provider->getVersion();
		$this->author  = $provider->getAuthor();
		// Author profile is not available in the current implementation.
		$this->requires     = $provider->getRequiresWordPressVersion();
		$this->tested       = $provider->getTested();
		$this->requiresPhp  = $provider->getRequiresPHPVersion();
		$this->homepage     = $provider->getViewURL();
		$this->downloadLink = $provider->getDownloadURL();
		$this->updateUri    = $provider->getDownloadURL();
		// Last updated date is not available in the current implementation.
		$this->tags = $provider->getTags();
	}
}
