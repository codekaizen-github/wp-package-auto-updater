<?php
/**
 * File containing PluginSlugParser class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Parser\Slug
 * @subpackage Slug
 */

namespace CodeKaizen\WPPackageAutoUpdater\Parser\Slug;

use CodeKaizen\WPPackageAutoUpdater\Contract\Value\PackageRootValueContract;
use CodeKaizen\WPPackageMetaProviderLocal\Contract\Value\SlugValueContract;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use UnexpectedValueException;

/**
 * Parser for plugin slugs.
 */
/**
 * PluginSlugParser class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Parser\Slug
 */
class PluginSlugParser implements SlugValueContract {

	/**
	 * The package root contract.
	 *
	 * @var PackageRootValueContract
	 */
	protected PackageRootValueContract $packageRoot;

	/**
	 * The file path.
	 *
	 * @var string
	 */
	protected string $filePath;

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected LoggerInterface $logger;

	/**
	 * The short slug.
	 *
	 * @var string|null
	 */
	protected ?string $shortSlug;

	/**
	 * The full slug.
	 *
	 * @var string|null
	 */
	protected ?string $fullSlug;

	/**
	 * Constructor.
	 *
	 * @param string                   $filePath Description for filePath.
	 * @param PackageRootValueContract $packageRoot Description for packageRoot.
	 * @param LoggerInterface          $logger Description for logger.
	 *
	 * @return mixed
	 */
	public function __construct(
		string $filePath,
		PackageRootValueContract $packageRoot,
		LoggerInterface $logger = new NullLogger()
	) {
		$this->filePath    = $filePath;
		$this->packageRoot = $packageRoot;
		$this->logger      = $logger;
		$this->shortSlug   = null;
		$this->fullSlug    = null;
	}

	/**
	 * Get the short slug for this plugin.
	 *
	 * @return string The short slug.
	 * @throws UnexpectedValueException If the slug cannot be determined.
	 */
	/**
	 * Get Short Slug.
	 *
	 * @return string The short slug for this plugin.
	 * @throws \UnexpectedValueException If the slug cannot be determined.
	 */
	public function getShortSlug(): string {
		if ( null === $this->shortSlug ) {
			$split             = explode( '/', $this->getFullSlug() );
			$firstFilePathPart = (string) array_shift( $split );
			$this->shortSlug   = basename( $firstFilePathPart, '.php' );
		}

		// Explicitly ensure the return value is a string.
		if ( '' === $this->shortSlug ) {
			$this->logger->error(
				'Failed to determine short slug.',
				[
					'filePath'  => $this->filePath,
					'shortSlug' => $this->shortSlug,
				]
			);
			throw new UnexpectedValueException( 'Failed to determine short slug.' );
		}

		return $this->shortSlug;
	}

	/**
	 * Get Full Slug.
	 *
	 * @return string The full slug for this plugin.
	 * @throws \UnexpectedValueException If the slug cannot be determined.
	 */
	public function getFullSlug(): string {
		if ( null === $this->fullSlug ) {
			$pluginRoot = $this->packageRoot->getPackageRoot();
			$dirEntries = scandir( $pluginRoot );

			$realPathFile = realpath( $this->filePath );

			if ( false === $realPathFile ) {
				$errorMsg = 'Could not resolve real path for file: ' . $this->filePath;
				$this->logger->error(
					$errorMsg,
					[
						'filePath' => $this->filePath,
					]
				);
				// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
				throw new UnexpectedValueException( $errorMsg );
			}

			$realPathFileBasename = basename( $realPathFile );
			// Resolve the real path of this plugin's directory.
			$realPathDir = dirname( $realPathFile );

			$slug = null;

			foreach ( $dirEntries as $entry ) {
				if ( '.' === $entry || '..' === $entry ) {
					continue;
				}

				$candidate = $pluginRoot . '/' . $entry;

				// Check directory plugins.
				if ( is_dir( $candidate ) ) {
					if ( realpath( $candidate ) === $realPathDir ) {
						$slug = $entry . '/' . $realPathFileBasename;
						break;
					}
				}

				// Check single-file plugins.
				if ( is_file( $candidate ) ) {
					if ( realpath( $candidate ) === $realPathFile ) {
						$slug = $entry;
						break;
					}
				}
			}
			if ( ! is_string( $slug ) ) {
				$this->logger->error(
					'Failed to determine full slug.',
					[
						'filePath' => $this->filePath,
						'slug'     => $slug,
					]
				);
				// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
				throw new UnexpectedValueException( "Expected slug to be string. Got $slug instead." );
			}
			$this->fullSlug = $slug;
		}
		return $this->fullSlug;
	}
}
