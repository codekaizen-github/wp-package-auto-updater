<?php
/**
 * CreateRemotePluginPackageMetaProviderFactoryFilterArgumentContract interface.
 *
 * @package CodeKaizen\WPPackageAutoUpdater\Contract\Argument\Filter\Factory\Provider\PackageMeta\Plugin\Remote
 */

namespace CodeKaizen\WPPackageAutoUpdater\Contract\Argument\Filter\Factory\Provider\PackageMeta\Plugin\Remote;

use Psr\Log\LoggerInterface;

interface CreateRemotePluginPackageMetaProviderFactoryFilterArgumentContract {
	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	public function getBaseURL(): string;
	/**
	 * Undocumented function
	 *
	 * @param string $baseURL Description for baseURL.
	 *
	 * @return void
	 */
	public function setBaseURL( string $baseURL ): void;
	/**
	 * Undocumented function
	 *
	 * @return string
	 */
	public function getMetaKey(): string;
	/**
	 * Undocumented function
	 *
	 * @param string $metaKey Description for metaKey.
	 *
	 * @return void
	 */
	public function setMetaKey( string $metaKey ): void;
	/**
	 * Undocumented function
	 *
	 * @return array<string,mixed>
	 */
	public function getHttpOptions(): array;
	/**
	 * Undocumented function
	 *
	 * @param array<string,mixed> $httpOptions Description for httpOptions.
	 *
	 * @return void
	 */
	public function setHttpOptions( array $httpOptions ): void;
	/**
	 * Undocumented function
	 *
	 * @return LoggerInterface
	 */
	public function getLogger(): LoggerInterface;
	/**
	 * Undocumented function
	 *
	 * @param LoggerInterface $logger Description for logger.
	 *
	 * @return void
	 */
	public function setLogger( LoggerInterface $logger ): void;
}
