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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
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
	 * Logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param string               $url         The URL to download.
	 * @param array<string, mixed> $httpOptions HTTP options for the downloader.
	 * @param LoggerInterface      $logger      Logger instance (optional).
	 */
	public function __construct( string $url, array $httpOptions = [], LoggerInterface $logger = new NullLogger() ) {
		$this->url         = $url;
		$this->httpOptions = $httpOptions;
		$this->logger      = $logger;
		$this->fileName    = null;
	}
	/**
	 * Download the file from the specified URL.
	 *
	 * @return void
	 * @throws Exception If the download fails.
	 */
	public function download() {
		$this->logger->debug( 'Starting download', [ 'url' => $this->url ] );
		$client   = new Client();
		$tempFile = tempnam( sys_get_temp_dir(), 'download_' );
		try {
			$response = $client->request(
				'GET',
				$this->url,
				array_merge(
					$this->httpOptions,
					[ 'sink' => $tempFile ]
				)
			);
			if ( $response->getStatusCode() === 200 ) {
				$this->fileName = $tempFile;
				$this->logger->info(
					'Download successful',
					[
						'file' => $tempFile,
						'url'  => $this->url,
					]
				);
			} else {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
				unlink( $tempFile );
				$this->logger->error(
					'Download failed: non-200 response',
					[
						'status' => $response->getStatusCode(),
						'url'    => $this->url,
					]
				);
				// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
				throw new Exception( 'Failed to download file: ' . $response->getStatusCode() );
			}
		} catch ( Exception $e ) {
			if ( file_exists( $tempFile ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
				unlink( $tempFile );
			}
			$this->logger->error(
				'Exception during download',
				[
					'exception' => $e,
					'url'       => $this->url,
				]
			);
			throw $e;
		}
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
