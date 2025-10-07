<?php
/**
 * File containing CheckInfoStrategy class.
 *
 * @package WPPackageAutoUpdater
 * @subpackage Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdater\Strategy;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckInfoStrategyContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PackageMetaContract;
use Psr\Log\LoggerInterface;

/**
 * Strategy for checking package information.
 */
/**
 * CheckInfoStrategy class.
 *
 * @package WPPackageAutoUpdater
 */
class CheckInfoStrategy implements CheckInfoStrategyContract {

	/**
	 * The local package meta provider.
	 *
	 * @var PackageMetaContract
	 */
	protected PackageMetaContract $localPackageMetaProvider;

	/**
	 * The formatter.
	 *
	 * @var CheckInfoFormatterContract
	 */
	protected CheckInfoFormatterContract $formatter;

	/**
	 * The logger.
	 *
	 * @var LoggerInterface
	 */
	private LoggerInterface $logger;

	/**
	 * Constructor.
	 *
	 * @param PackageMetaContract        $localPackageMetaProvider The local package meta provider.
	 * @param CheckInfoFormatterContract $formatter               The formatter.
	 * @param LoggerInterface            $logger                  The logger.
	 */
	/**
	 * Constructor.
	 *
	 * @param PackageMetaContract        $localPackageMetaProvider Description for localPackageMetaProvider.
	 * @param CheckInfoFormatterContract $formatter Description for formatter.
	 * @param LoggerInterface            $logger Description for logger.
	 *
	 * @return mixed
	 */
	public function __construct(
		PackageMetaContract $localPackageMetaProvider,
		CheckInfoFormatterContract $formatter,
		LoggerInterface $logger
	) {
		$this->localPackageMetaProvider = $localPackageMetaProvider;
		$this->formatter                = $formatter;
		$this->logger                   = $logger;
	}
	/**
	 * Check package info and inject custom data.
	 *
	 * @param bool                  $false  Always false initially.
	 * @param array<string, string> $action The type of information being requested.
	 * @param object                $arg    The arguments passed to the API request.
	 * @return bool|object                  False if no action taken or object with info.
	 */
	public function checkInfo( bool $false, array $action, object $arg ): bool|object {
		// Check if this is for our package.
		if ( ! isset( $arg->slug ) || $arg->slug !== $this->localPackageMetaProvider->getShortSlug() ) {
			return $false;
		}
		$this->logger->debug( 'Providing package info for: ' . $arg->slug );

		// Get metadata from remote source.
		$meta = $this->formatter->formatForCheckInfo();

		$this->logger->debug( 'Returning package info with properties: ' . implode( ', ', array_keys( (array) $meta ) ) );

		return $meta;
	}
}
