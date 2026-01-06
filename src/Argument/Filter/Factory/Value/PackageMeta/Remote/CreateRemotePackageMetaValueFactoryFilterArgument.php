<?php
/**
 * File containing CreateRemotePackageMetaValueFactoryFilterArgument class.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Argument\Filter\Factory\Value\PackageMeta\Remote
 */

namespace CodeKaizen\WPPackageAutoUpdater\Argument\Filter\Factory\Value\PackageMeta\Remote;

// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageAutoUpdater\Contract\Argument\Filter\Factory\Value\PackageMeta\Remote\CreateRemotePackageMetaValueFactoryFilterArgumentContract;

use Psr\Log\LoggerInterface;

// phpcs:disable Generic.Files.LineLength.TooLong
/**
 * Undocumented class
 */
class CreateRemotePackageMetaValueFactoryFilterArgument implements CreateRemotePackageMetaValueFactoryFilterArgumentContract {
// phpcs:enable Generic.Files.LineLength.TooLong

	/**
	 * The base URL.
	 *
	 * @var string
	 */
	protected string $baseURL;

	/**
	 * The meta key.
	 *
	 * @var string
	 */
	protected string $metaKey;

	/**
	 * The HTTP options.
	 *
	 * @var array<string,mixed>
	 */
	protected array $httpOptions;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param string              $baseURL     The base URL.
	 * @param string              $metaKey     The meta key.
	 * @param array<string,mixed> $httpOptions The HTTP options.
	 * @param LoggerInterface     $logger      The logger instance.
	 */
	public function __construct(
		string $baseURL,
		string $metaKey,
		array $httpOptions,
		LoggerInterface $logger
	) {
		$this->baseURL     = $baseURL;
		$this->metaKey     = $metaKey;
		$this->httpOptions = $httpOptions;
		$this->logger      = $logger;
	}
	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	public function getBaseURL(): string {
		return $this->baseURL;
	}
	/**
	 * Undocumented function
	 *
	 * @param string $baseURL Description for baseURL.
	 *
	 * @return void
	 */
	public function setBaseURL( string $baseURL ): void {
		$this->baseURL = $baseURL;
	}
	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	public function getMetaKey(): string {
		return $this->metaKey;
	}
	/**
	 * Undocumented function
	 *
	 * @param string $metaKey Description for metaKey.
	 *
	 * @return void
	 */
	public function setMetaKey( string $metaKey ): void {
		$this->metaKey = $metaKey;
	}
	/**
	 * Undocumented function
	 *
	 * @return array<string,mixed>
	 */
	public function getHttpOptions(): array {
		return $this->httpOptions;
	}
	/**
	 * Undocumented function
	 *
	 * @param array<string,mixed> $httpOptions Description for httpOptions.
	 *
	 * @return void
	 */
	public function setHttpOptions( array $httpOptions ): void {
		$this->httpOptions = $httpOptions;
	}
	/**
	 * Undocumented function
	 *
	 * @return LoggerInterface
	 */
	public function getLogger(): LoggerInterface {
		return $this->logger;
	}
	/**
	 * Undocumented function
	 *
	 * @param LoggerInterface $logger Description for logger.
	 *
	 * @return void
	 */
	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}
}
