<?php
/**
 * Test for PluginCheckInfoHook.
 *
 * @package WPPackageAutoUpdater
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\PluginCheckInfoHook;
use CodeKaizen\WPPackageMetaProviderContract\Contract\PluginPackageMetaProviderFactoryContract;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Tests for the PluginCheckInfoHook class.
 */
class PluginCheckInfoHookTest extends TestCase {

	/**
	 * Test that init() adds the filter.
	 */
	public function testInitAddsFilter(): void {
		// Mock the dependencies.
		$localFactory  = Mockery::mock( PluginPackageMetaProviderFactoryContract::class );
		$remoteFactory = Mockery::mock( PluginPackageMetaProviderFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );

		$sut = new PluginCheckInfoHook( $localFactory, $remoteFactory, $logger );
		// Set up expectations.
		WP_Mock::expectFilterAdded(
			'plugins_api',
			[ $sut , 'checkInfo' ],
			10,
			3
		);

		// Create the instance and call init.
		$sut->init();

		// This is important for WP_Mock assertions to work.
		$this->assertConditionsMet();
	}
}
