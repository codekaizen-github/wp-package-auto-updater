<?php
/**
 * File containing FileDownloaderClient class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Client\Downloader
 * @subpackage Client\Downloader
 */

namespace CodeKaizen\WPPackageAutoUpdater\Client\Downloader;

use CodeKaizen\WPPackageAutoUpdater\Contract\Client\Downloader\FileDownloaderClientContract;
use Exception;
use GuzzleHttp\Client;

/**
 * Undocumented class
 */
class FileDownloaderClient implements FileDownloaderClientContract {

	/**
	 * The downloaded file name.
	 *
	 * @var string|null
	 */
	protected ?string $fileName;
	/**
	 * The URL to download.
	 *
	 * @var string
	 */
	protected string $url;
	/**
	 * HTTP options for the downloader.
	 *
	 * @var array<string, mixed>
	 */
	protected array $httpOptions;

	/**
	 * Constructor.
	 *
	 * @param string               $url         The URL to download.
	 * @param array<string, mixed> $httpOptions HTTP options for the downloader.
	 */
	public function __construct( string $url, array $httpOptions = [] ) {
		// Constructor implementation if needed.
		$this->url         = $url;
		$this->httpOptions = $httpOptions;
		$this->fileName    = null;
	}
	/**
	 * Download the file from the specified URL.
	 *
	 * @return void
	 * @throws Exception If the download fails.
	 */
	public function download() {
		$client   = new Client();
		$tempFile = tempnam( sys_get_temp_dir(), 'download_' );
		$response = $client->request( 'GET', $this->url, array_merge( $this->httpOptions, [ 'sink' => $tempFile ] ) );
		if ( $response->getStatusCode() === 200 ) {
			$this->fileName = $tempFile;
		} else {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
			unlink( $tempFile );
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new Exception( 'Failed to download file: ' . $response->getStatusCode() );
		}
		$this->fileName = $tempFile;
	}
	/**
	 * Get the downloaded file name.
	 *
	 * @return string|null The file name or null if not downloaded.
	 */
	public function getFileName(): ?string {
		return $this->fileName;
	}
}
