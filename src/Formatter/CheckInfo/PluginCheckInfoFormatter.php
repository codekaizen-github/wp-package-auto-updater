<?php
/**
 * File containing CheckInfoFormatterPlugin class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaContract;
use stdClass;

/**
 * CheckInfoFormatterPlugin class.
 *
 * @package WPPackageAutoUpdater
 */
class CheckInfoFormatterPlugin implements CheckInfoFormatterContract {

	/**
	 * The plugin package meta provider.
	 *
	 * @var PluginPackageMetaContract
	 */
	private PluginPackageMetaContract $provider;

	/**
	 * Constructor.
	 *
	 * @param PluginPackageMetaContract $provider Description for provider.
	 *
	 * @return mixed
	 */
	public function __construct( PluginPackageMetaContract $provider ) {
		$this->provider = $provider;
	}
	/**
	 * Format data for check info.
	 *
	 * @return object The formatted object containing plugin information.
	 */
	public function formatForCheckInfo(): object {
		$stdObj          = new stdClass();
		$stdObj->name    = $this->provider->getName();
		$stdObj->slug    = $this->provider->getShortSlug();
		$stdObj->version = $this->provider->getVersion();
		$stdObj->author  = $this->provider->getAuthor();
		// Author profile is not available in the current implementation.
		$stdObj->requires     = $this->provider->getRequiresWordPressVersion();
		$stdObj->tested       = $this->provider->getTested();
		$stdObj->requiresPhp  = $this->provider->getRequiresPHPVersion();
		$stdObj->homepage     = $this->provider->getViewURL();
		$stdObj->downloadLink = $this->provider->getDownloadURL();
		$stdObj->updateUri    = $this->provider->getDownloadURL();
		// Last updated date is not available in the current implementation.
		$stdObj->sections = $this->provider->getSections();
		$stdObj->tags     = $this->provider->getTags();
		// WordPress expects these properties for plugin information.
		$stdObj->external = true; // indicates this is an external package.
		return $stdObj;
	}
}
