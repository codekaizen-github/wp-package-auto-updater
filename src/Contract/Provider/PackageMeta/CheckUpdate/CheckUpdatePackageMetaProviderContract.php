<?php
/**
 * Package Meta Provider Contract
 *
 * This interface defines the contract for WordPress package metadata providers.
 *
 * @package CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Provider\PackageMeta\CheckUpdate;

interface CheckUpdatePackageMetaProviderContract {

	/**
	 * Get the full slug of the package.
	 *
	 * @return string The full slug, including vendor/package format.
	 */
	public function getFullSlug(): string;

	/**
	 * Get the short slug of the package.
	 *
	 * @return string The package short slug.
	 */
	public function getShortSlug(): string;

	/**
	 * Get the version of the package.
	 *
	 * @return string|null The package version or null if not available.
	 */
	public function getVersion(): ?string;

	/**
	 * Get the URL to view package details.
	 *
	 * @return string|null The package view URL or null if not available.
	 */
	public function getViewURL(): ?string;

	/**
	 * Get the URL to download the package.
	 *
	 * @return string|null The package download URL or null if not available.
	 */
	public function getDownloadURL(): ?string;

	/**
	 * Get the required WordPress version for the package.
	 *
	 * @return string|null The required WordPress version or null if not available.
	 */
	public function getRequiresWordPressVersion(): ?string;

	/**
	 * Get the required PHP version for the package.
	 *
	 * @return string|null The required PHP version or null if not available.
	 */
	public function getRequiresPHPVersion(): ?string;

	/**
	 * Get the icons for the package.
	 *
	 * @return array<string,string> An array of icon URLs, keyed by size or identifier.
	 */
	public function getIcons(): array;

	/**
	 * Get the banners for the package.
	 *
	 * @return array<string,string> An array of banner URLs, keyed by size or identifier.
	 */
	public function getBanners(): array;

	/**
	 * Get the RTL banners for the package.
	 *
	 * @return array<string,string> An array of RTL banner URLs, keyed by size or identifier.
	 */
	public function getBannersRTL(): array;
}
