<?php
/**
 * Test for PluginCheckUpdateHook.
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckUpdate
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckUpdate;

use CodeKaizen\WPPackageAutoUpdater\Hook\CheckUpdate\PluginCheckUpdateHook;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Provider\PackageMeta\PluginPackageMetaProviderFactoryContract;
use CodeKaizen\WPPackageMetaProviderContract\Contract\Provider\PackageMeta\PluginPackageMetaProviderContract;
use Exception;
use Mockery;
use Psr\Log\LoggerInterface;
use stdClass;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * Tests for the PluginCheckUpdateHook class.
 */
class PluginCheckUpdateHookTest extends TestCase {

	/**
	 * Test that init() adds the filter.
	 */
	public function testInitAddsFilter(): void {
		// Mock the dependencies.
		$localFactory  = Mockery::mock( PluginPackageMetaProviderFactoryContract::class );
		$remoteFactory = Mockery::mock( PluginPackageMetaProviderFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );

		$sut = new PluginCheckUpdateHook( $localFactory, $remoteFactory, $logger );
		// Set up expectations.
		WP_Mock::expectFilterAdded(
			'pre_set_site_transient_update_plugins',
			[ $sut , 'checkUpdate' ]
		);

		// Create the instance and call init.
		$sut->init();

		// This is important for WP_Mock assertions to work.
		$this->assertConditionsMet();
	}
	/**
	 * Mock a CheckUpdateStrategy and have it throw an exception on the checkUpdate call.
	 * Test to ensure that the sut gracefully handles the exception and logs an error.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testExceptionHandlingInCheckUpdate(): void {
		// Mock the dependencies.
		$localFactory = Mockery::mock( PluginPackageMetaProviderFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( PluginPackageMetaProviderContract::class )
		);
		$remoteFactory = Mockery::mock( PluginPackageMetaProviderFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( PluginPackageMetaProviderContract::class )
		);
		$logger = Mockery::mock( LoggerInterface::class );
		$sut    = new PluginCheckUpdateHook( $localFactory, $remoteFactory, $logger );
		Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Formatter\CheckUpdate\PluginCheckUpdateFormatter'
		);
		$strategy = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Strategy\CheckUpdateStrategy'
		);
		$strategy->shouldReceive( 'checkUpdate' )->andThrow( new Exception( 'Test exception' ) );
		$logger->shouldReceive( 'error' );
		// Call the method under test.
		$transient = new stdClass();
		$result    = $sut->checkUpdate( $transient );
		$this->assertSame( $transient, $result );
	}
}
