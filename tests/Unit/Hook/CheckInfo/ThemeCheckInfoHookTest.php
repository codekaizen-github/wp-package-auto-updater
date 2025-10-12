<?php
/**
 * Test for ThemeCheckInfoHook.
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\ThemeCheckInfoHook;
use CodeKaizen\WPPackageMetaProviderContract\Contract\ThemePackageMetaProviderFactoryContract;
use Mockery;
use Psr\Log\LoggerInterface;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Tests for the ThemeCheckInfoHook class.
 */
class ThemeCheckInfoHookTest extends TestCase {

	/**
	 * Test that init() adds the filter.
	 */
	public function testInitAddsFilter(): void {
		// Mock the dependencies.
		$localFactory  = Mockery::mock( ThemePackageMetaProviderFactoryContract::class );
		$remoteFactory = Mockery::mock( ThemePackageMetaProviderFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );

		$sut = new ThemeCheckInfoHook( $localFactory, $remoteFactory, $logger );
		// Set up expectations.
		WP_Mock::expectFilterAdded(
			'themes_api',
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
