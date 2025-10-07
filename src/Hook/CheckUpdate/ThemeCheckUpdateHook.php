<?php

namespace CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate;

use Psr\Log\LoggerInterface;
use CodeKaizen\WPPackageAutoUpdater\Contract\InitializerContract;
use CodeKaizen\WPPackageAutoUpdater\Contract\Strategy\CheckUpdateStrategyContract;
use CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate\CheckUpdateFormatterTheme;
use CodeKaizen\WPPackageAutoUpdater\Strategy\CheckUpdateStrategy;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
use stdClass;

class ThemeCheckUpdateHook implements InitializerContract, CheckUpdateStrategyContract {

	protected ThemePackageMetaProviderFactoryContract $localPackageMetaProviderFactory;
	protected ThemePackageMetaProviderFactoryContract $remotePackageMetaProviderFactory;
	protected LoggerInterface $logger;
	public function __construct( ThemePackageMetaProviderFactoryContract $localPackageMetaProviderFactory, ThemePackageMetaProviderFactoryContract $remotePackageMetaProviderFactory, LoggerInterface $logger ) {
		$this->localPackageMetaProviderFactory  = $localPackageMetaProviderFactory;
		$this->remotePackageMetaProviderFactory = $remotePackageMetaProviderFactory;
		$this->logger                           = $logger;
	}
	public function init(): void {
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'checkUpdate' ) );
	}
	public function checkUpdate( stdClass $transient ): stdClass {
		$formatter   = new CheckUpdateFormatterTheme();
		$checkUpdate = new CheckUpdateStrategy( $this->localPackageMetaProviderFactory->create(), $this->remotePackageMetaProviderFactory->create(), $formatter, $this->logger );
		return $checkUpdate->checkUpdate( $transient );
	}
}
