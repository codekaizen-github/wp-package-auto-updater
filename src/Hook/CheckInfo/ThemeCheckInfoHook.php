<?php

namespace CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckInfoStrategyContract;
use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo\CheckInfoFormatterTheme;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckInfoStrategy;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;

class ThemeCheckInfoHook implements InitializerContract, CheckInfoStrategyContract {

	protected ThemePackageMetaProviderFactoryContract $localPackageMetaProviderFactory;
	protected ThemePackageMetaProviderFactoryContract $remotePackageMetaProviderFactory;
	protected LoggerInterface $logger;
	public function __construct( ThemePackageMetaProviderFactoryContract $localPackageMetaProviderFactory, ThemePackageMetaProviderFactoryContract $remotePackageMetaProviderFactory, LoggerInterface $logger ) {
		$this->localPackageMetaProviderFactory  = $localPackageMetaProviderFactory;
		$this->remotePackageMetaProviderFactory = $remotePackageMetaProviderFactory;
		$this->logger                           = $logger;
	}
	public function init(): void {
		add_filter( 'themes_api', array( $this, 'checkInfo' ), 10, 3 );
	}
	/**
	 * Check theme info and inject custom data.
	 *
	 * @param bool                  $false  Always false initially.
	 * @param array<string, string> $action The type of information being requested.
	 * @param object                $arg    The arguments passed to the API request.
	 * @return bool|object                  False if no action taken or object with info.
	 */
	public function checkInfo( bool $false, array $action, object $arg ): bool|object {
		$formatter = new CheckInfoFormatterTheme( $this->remotePackageMetaProviderFactory->create() );
		$checkInfo = new CheckInfoStrategy( $this->localPackageMetaProviderFactory->create(), $formatter, $this->logger );
		return $checkInfo->checkInfo( $false, $action, $arg );
	}
}
