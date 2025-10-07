<?php
/**
 * File containing CheckInfoFormatterTheme class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaContract;
use stdClass;

/**
 * CheckInfoFormatterTheme class.
 *
 * @package WPPackageAutoUpdater
 */
class CheckInfoFormatterTheme implements CheckInfoFormatterContract {

	/**
	 * The theme package meta provider.
	 *
	 * @var ThemePackageMetaContract
	 */
	private ThemePackageMetaContract $provider;

	/**
	 * Constructor.
	 *
	 * @param ThemePackageMetaContract $provider Description for provider.
	 *
	 * @return mixed
	 */
	public function __construct( ThemePackageMetaContract $provider ) {
		$this->provider = $provider;
	}
	/**
	 * Format data for check info.
	 *
	 * @return object The formatted object containing theme information.
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
		$stdObj->tags = $this->provider->getTags();
		return $stdObj;
	}
}
