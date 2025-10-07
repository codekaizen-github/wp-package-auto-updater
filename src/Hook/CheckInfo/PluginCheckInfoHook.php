<?php

namespace CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckInfoStrategyContract;
use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckInfo\CheckInfoFormatterPlugin;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckInfoStrategy;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaProviderFactoryContract;

class PluginCheckInfoHook implements InitializerContract, CheckInfoStrategyContract {

	protected PluginPackageMetaProviderFactoryContract $localPackageMetaProviderFactory;
	protected PluginPackageMetaProviderFactoryContract $remotePackageMetaProviderFactory;
	protected LoggerInterface $logger;
	public function __construct( PluginPackageMetaProviderFactoryContract $localPackageMetaProviderFactory, PluginPackageMetaProviderFactoryContract $remotePackageMetaProviderFactory, LoggerInterface $logger ) {
		$this->localPackageMetaProviderFactory  = $localPackageMetaProviderFactory;
		$this->remotePackageMetaProviderFactory = $remotePackageMetaProviderFactory;
		$this->logger                           = $logger;
	}
	public function init(): void {
		add_filter( 'plugins_api', array( $this, 'checkInfo' ), 10, 3 );
	}
	/**
	 * Check plugin info and inject custom data.
	 *
	 * @param bool                  $false  Always false initially.
	 * @param array<string, string> $action The type of information being requested.
	 * @param object                $arg    The arguments passed to the API request.
	 * @return bool|object                  False if no action taken or object with info.
	 */
	public function checkInfo( bool $false, array $action, object $arg ): bool|object {
		$formatter = new CheckInfoFormatterPlugin( $this->remotePackageMetaProviderFactory->create() );
		$checkInfo = new CheckInfoStrategy( $this->localPackageMetaProviderFactory->create(), $formatter, $this->logger );
		return $checkInfo->checkInfo( $false, $action, $arg );
	}
}
