<?php
/**
 * File containing StandardCheckInfoStrategy class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Strategy\CheckInfo
 * @subpackage Strategy
 */

namespace CodeKaizen\WPPackageAutoUpdater\Strategy\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\Formatter\CheckInfo\CheckInfoFormatterContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckInfoStrategyContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Value\PackageMeta\PackageMetaValueContract;
use Psr\Log\LoggerInterface;

/**
 * Strategy for checking package information.
 */
/**
 * StandardCheckInfoStrategy class.
 *
 *  @package CodeKaizen\WPPackageAutoUpdater\Strategy
 */
class StandardCheckInfoStrategy implements CheckInfoStrategyContract {

	/**
	 * The local package meta provider.
	 *
	 * @var PackageMetaValueContract
	 */
	protected PackageMetaValueContract $localPackageMetaProvider;

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
	 * @param PackageMetaValueContract        $localPackageMetaProvider The local package meta provider.
	 * @param CheckInfoFormatterContract $formatter               The formatter.
	 * @param LoggerInterface            $logger                  The logger.
	 */
	/**
	 * Constructor.
	 *
	 * @param PackageMetaValueContract   $localPackageMetaProvider Description for localPackageMetaProvider.
	 * @param CheckInfoFormatterContract $formatter Description for formatter.
	 * @param LoggerInterface            $logger Description for logger.
	 *
	 * @return mixed
	 */
	public function __construct(
		PackageMetaValueContract $localPackageMetaProvider,
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
	 * @param bool|object $false  Always false initially.
	 * @param string      $action The type of information being requested.
	 * @param object      $arg    The arguments passed to the API request.
	 * @return bool|object                  False if no action taken or object with info.
	 *
	 * phpcs:disable Universal.NamingConventions.NoReservedKeywordParameterNames.falseFound
	 */
	public function checkInfo( bool|object $false, string $action, object $arg ): bool|object {
		$this->logger->debug(
			'Entering StandardCheckInfoStrategy::checkInfo',
			[
				'false'  => $false,
				'action' => $action,
				'arg'    => $arg,
			]
		);
		// phpcs:enable Universal.NamingConventions.NoReservedKeywordParameterNames.falseFound
		// Check if this is for our package.
		if ( ! isset( $arg->slug ) || $arg->slug !== $this->localPackageMetaProvider->getShortSlug() ) {
			$this->logger->debug(
				'Slug does not match local package meta provider. Returning false.',
				[
					'false'  => $false,
					'action' => $action,
					'arg'    => $arg,
				]
			);
			return $false;
		}
		$this->logger->debug( 'Providing package info for: ' . $arg->slug );

		// Get metadata from remote source.
		$meta = $this->formatter->create();

		$this->logger->debug(
			'Returning package info with properties: ' . implode(
				', ',
				array_keys( (array) $meta )
			)
		);

		return $meta;
	}
}
