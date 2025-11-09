<?php
/**
 * File containing FileDownloaderClientContract Interface class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Contract\Client\Downloader
 * @subpackage Client\Downloader
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Client\Downloader;

interface FileDownloaderClientContract {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function download();
	/**
	 * Undocumented function
	 *
	 * @return string|null
	 */
	public function getFileName(): ?string;
}
