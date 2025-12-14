<?php
/**
 * Test for PluginCheckInfoHook.
 *
 *  @package CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckInfo
 */

namespace CodeKaizen\WPPackageAutoUpdaterTests\Unit\Hook\CheckInfo;

use CodeKaizen\WPPackageAutoUpdater\Hook\CheckInfo\PluginCheckInfoHook;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Factory\Service\Value\PackageMeta\PluginPackageMetaValueServiceFactoryContract;
// phpcs:ignore Generic.Files.LineLength.TooLong
use CodeKaizen\WPPackageMetaProviderContract\Contract\Service\Value\PackageMeta\PluginPackageMetaValueServiceContract;
use Exception;
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
		$localFactory  = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$remoteFactory = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$logger        = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );

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
	/**
	 * Mock a StandardCheckInfoStrategy and have it throw an exception on the checkInfo call.
	 * Test to ensure that the sut gracefully handles the exception and logs an error.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function testExceptionHandlingInCheckInfo(): void {
		// Mock the dependencies.
		$localFactory = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$localFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( PluginPackageMetaValueServiceContract::class )
		);
		$remoteFactory = Mockery::mock( PluginPackageMetaValueServiceFactoryContract::class );
		$remoteFactory->shouldReceive( 'create' )->andReturn(
			Mockery::mock( PluginPackageMetaValueServiceContract::class )
		);
		$logger = Mockery::mock( LoggerInterface::class );
		$logger->shouldReceive( 'debug' );
		$logger->shouldReceive( 'info' );
		$logger->shouldReceive( 'error' );
		$sut = new PluginCheckInfoHook( $localFactory, $remoteFactory, $logger );
		// phpcs:disable Generic.Files.LineLength.TooLong
		Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Factory\StandardClass\CheckInfo\PluginCheckInfoStandardClassFactory'
		);
		// phpcs:enable Generic.Files.LineLength.TooLong
		$strategy = Mockery::mock(
			'overload:CodeKaizen\WPPackageAutoUpdater\Strategy\CheckInfo\StandardCheckInfoStrategy'
		);
		$strategy->shouldReceive( 'checkInfo' )->andThrow( new Exception( 'Test exception' ) );
		$logger->shouldReceive( 'error' );
		// Call the method under test.
		$result = $sut->checkInfo( false, 'plugin_information', (object) [] );
		$this->assertFalse( $result );
	}
}
